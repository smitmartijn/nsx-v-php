<?php

require("nsx-v.controller.class.php");
require("nsx-v.edge.class.php");
require("nsx-v.logicalswitch.class.php");
require("nsx-v.transportzone.class.php");

class NSX_v_API
{
  protected $nsx_manager;
  protected $nsx_username;
  protected $nsx_password;

  protected $class_transportzone;
  protected $class_controller;
  protected $class_edge;
  protected $class_switch;

  protected $scopeId    = "globalroot-0";

  /**
   * Configure details of NSX Manager (IP/DNS Name, username & password).
   * Store details for use in API_Call()
   *
   * @param string $nsx_manager IP Address or DNS Name of NSX Manager
   * @param string $username Login username
   * @param string $password Login password
   */
  public function setLoginDetails($nsx_manager, $username, $password)
  {
    $this->nsx_manager  = $nsx_manager;
    $this->nsx_username = $username;
    $this->nsx_password = $password;
  }

  /**
   * Create Controller object and return it
   *
   * @return object Controller class object
   */
  public function Controller()
  {
    if(empty($this->class_controller))
      $this->class_controller = new \NSX_v_API\Controller($this);
    return $this->class_controller;
  }

  /**
   * Create Edge object and return it
   *
   * @return object Edge class object
   */
  public function Edge()
  {
    if(empty($this->class_edge))
      $this->class_edge = new \NSX_v_API\Edge($this);
    return $this->class_edge;
  }

  /**
   * Create LogicalSwitch object and return it
   *
   * @return object LogicalSwitch class object
   */
  public function LogicalSwitch()
  {
    if(empty($this->class_switch))
      $this->class_switch = new \NSX_v_API\LogicalSwitch($this);
    return $this->class_switch;
  }

  /**
   * Create TransportZone object and return it
   *
   * @return object TransportZone class object
   */
  public function TransportZone()
  {
    if(empty($this->class_transportzone))
      $this->class_transportzone = new \NSX_v_API\TransportZone($this);
    return $this->class_transportzone;
  }

  /**
   * Execute an API call
   *
   * @param string $method HTTP Method; GET, POST, PUT, DELETE
   * @param string $url    HTTP URL (after NSX Manager URL)
   * @param string $data   Optional request body (in XML) for when doing a POST or PUT
   *
   * @return string|array HTTP Request result (body text) or
   *                      array of request result and HTTP status code
   */
  protected function API_Call($method, $url, $data = false)
  {
    $curl = curl_init();

    if(empty($this->nsx_manager) || empty($this->nsx_username) || empty($this->nsx_password))
      throw new \Exception("NSX Manager details have not been configured.");

    $url = "https://".$this->nsx_manager.$url;
    $username = $this->nsx_username;
    $password = $this->nsx_password;

    switch ($method)
    {
      case "POST":
        curl_setopt($curl, CURLOPT_POST, 1);
        if ($data)
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
      break;
      case "PUT":
        curl_setopt($curl, CURLOPT_PUT, 1);
      break;
      case "DELETE":
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
      break;
      default:
        if ($data)
          $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // set authentication
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, $username.":".$password);
    // set url and other options
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

    if($method == "POST" || $method == "PUT" || $method == "DELETE")
    {
      curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
      curl_setopt($curl, CURLOPT_HEADER, true);
    }

    // execute operation
    $result = curl_exec($curl);

    // catch error
    if(curl_error($curl))
      $result = curl_error($curl);

    $return_info = array();
    $return_info['status'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    // strip header from the response
    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $body = substr($result, $header_size);
    $return_info['body']   = $body;

    // cleanup
    curl_close($curl);

    // if we're changing something, return the body and http status code
    if($method == "POST" || $method == "PUT" || $method == "DELETE")
      return $return_info;
    else
      return $result;
  }

  /**
   * Convert a XML string to an array
   *
   * @param string $xml_str XML formatted string
   *
   * @return array Converted array from input XML string
   */
  protected function XML_to_Array($xml_str)
  {
    // simple xml to array
    $xml   = simplexml_load_string($xml_str);
    $json  = json_encode($xml);
    $array = json_decode($json, TRUE);
    return $array;
  }
}
