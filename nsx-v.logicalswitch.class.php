<?php

namespace NSX_v_API;

class LogicalSwitch extends \NSX_v_API
{
  private $parent;

  public function __construct($parent)
  {
    $this->parent = $parent;
  }

  /**
   * Get all info for logical switches
   *
   * @param string $switchId Optional name or identifier to return a single logical switch
   *
   * @return array All data on Logical Switches
   */
  public function Get($switchId = "")
  {
    if(!empty($switchId) && preg_match("/^virtualwire\-(\d+)$/", $switchId))
      $url_add = "/".$switchId;

    $xml_str  = $this->parent->API_Call("GET", "/api/2.0/vdn/virtualwires".$url_add."?pagesize=1000&startindex=0");
    $switches = $this->parent->XML_to_Array($xml_str);

    // look for a logical switch based on a name
    if(!empty($switchId) && !preg_match("/^virtualwire\-(\d+)$/", $switchId))
    {
      $switch_info = array();
      foreach($switches['dataPage']['virtualWire'] as $id => $switchInfo)
      {
        if($switchInfo['name'] == $switchId)
          $switch_info = $switchInfo;
      }
      return $switch_info;
    }

    return $switches;
  }

  /**
   * Create a new Logical Switch
   *
   * @param string $switchName Name for the new Logical Switch
   *
   * @return string|int New logical switch identifier ("virtualwire-XX") or the
   *                    HTTP status error code (if the request fails)
   */
  public function Create($switchName, $description = "", $tenantId = "")
  {
    // form XML request for creating a logical switch
    $request_body  = "<virtualWireCreateSpec>";
    $request_body .= "  <name>".$switchName."</name>";
    $request_body .= "  <description>".$description."</description>";
    $request_body .= "  <tenantId>".$tenantId."</tenantId>";
    $request_body .= "</virtualWireCreateSpec>";

    $result = $this->parent->API_Call("POST", "/api/2.0/vdn/scopes/".$this->parent->vdnScopeId."/virtualwires", $request_body);

    // code 201 == created
    if($result['status'] == 201)
      return $result['body'];
    else
      return $result['status'];
  }

  /**
   * Delete a Logical Switch
   *
   * @param string $switchId Identifier of the logical switch we are deleting
   *
   * @return int HTTP Status code - 200 == deleted
   */
  public function Delete($switchId)
  {
    if(!preg_match("/^virtualwire\-(\d+)$/", $switchId))
      throw new \Exception("switchId needs to be in format 'virtualwire-XX'");

    $result = $this->parent->API_Call("DELETE", "/api/2.0/vdn/virtualwires/".$switchId);

    // code 200 == deleted
    return $result['status'];
  }
}

?>
