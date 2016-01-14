<?php

namespace NSX_v_API;

class Controller extends \NSX_v_API
{
  private $parent;

  public function __construct($parent)
  {
    $this->parent = $parent;
  }

  /**
   * Get all data from existing NSX Controllers
   *
   * @return array All info on NSX Controllers
   */
  public function Get()
  {
    // form NSX API call and execute
    $xml_str = $this->parent->API_Call("GET", "/api/2.0/vdn/controller");
    return $this->parent->XML_to_Array($xml_str);
  }
}


?>
