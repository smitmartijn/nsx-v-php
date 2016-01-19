<?php

namespace NSX_v_API;

class TransportZone extends \NSX_v_API
{
  private $parent;

  public function __construct($parent)
  {
    $this->parent = $parent;
  }

  /**
   * Get all data for transport zones
   *
   * @param string $transportZoneId Optional name or identifier to return a single transport zone
   *
   * @return array All requested data on transport zone(s)
   */
  public function Get($transportZoneId = "")
  {
    $xml_str  = $this->parent->API_Call("GET", "/api/2.0/vdn/scopes");
    $array_tz = $this->parent->XML_to_Array($xml_str);

    // if we're looking for a specific transport zone based on a name, look for
    // it and discard the rest. Return empty array if the transport zone isn't found
    if(!empty($transportZoneId))
    {
      foreach($array_tz['vdnScope'] as $id => $tzInfo)
      {
        // match on name and get edge info
        if($tzInfo['name'] == $transportZoneId)
          return $tzInfo;
      }
      return array();
    }

    return $array_tz;
  }

  /**
   * Create a new Transport Zone
   *
   * @param string $transportZoneName Name for the new Transport Zone
   * @param string $controlPlaneMode Hybrid, Unicast or Multicast mode
   *
   * @return string|int New logical switch identifier ("virtualwire-XX") or the
   *                    HTTP status error code (if the request fails)
   */
  public function Create($transportZoneName, $clusterMoId, $controlPlaneMode = "hybrid")
  {
    // sanity check on parameters
    $controlMode = "";
    if($controlPlaneMode == "hybrid")
      $controlMode = "HYBRID_MODE";
    elseif($controlPlaneMode == "multicast")
      $controlMode = "MULTICAST_MODE";
    elseif($controlPlaneMode == "unicast")
      $controlMode = "UNICAST_MODE";
    else
      throw new Exception("Control Plane Mode '".$controlPlaneMode."' not allowed!");

    // form XML request for creating a logical switch
    $request_body .= "<vdnScope>";
    $request_body .= " <name>".$transportZoneName."</name>";

    $request_body .= " <clusters>";
    $request_body .= "  <cluster>";
    $request_body .= "   <cluster>";
    $request_body .= "    <objectId>".$clusterMoId."</objectId>";
    $request_body .= "   </cluster>";
    $request_body .= "  </cluster>";
    $request_body .= " </clusters>";


    $request_body .= " <controlPlaneMode>".$controlMode."</controlPlaneMode>";
    $request_body .= " <virtualWireCount>1</virtualWireCount>";
    $request_body .= "</vdnScope>";

    $result = $this->parent->API_Call("POST", "/api/2.0/vdn/scopes", $request_body);

    // code 201 == created
    if($result['status'] == 201)
      return $result['body'];
    else
      return $result['status'];
  }


  /**
   * Delete an existing Transport Zone
   *
   * @param string $transportZone Identifier or name of the Transport Zone we want to delete
   *
   * @return int HTTP Status code - 200 == deleted
   */
  public function Delete($transportZone)
  {
    // if the transportzone is not formatted as 'vdnscope-XX' - find the ID from the name
    if(!preg_match("/^vdnscope\-(\d+)$/", $transportZone))
    {
      // get all transport zones and loop through them
      $zones = $this->Get();
      $found_tz_id = false;
      foreach($zones['vdnScope'] as $id => $TZInfo)
      {
        // match on name and use the objectId for the scope id
        if($TZInfo['name'] == $transportZone)
        {
          $transportZoneId = $TZInfo['objectId'];
          $found_tz_id = true;
          break;
        }
      }
    }
    else {
      $transportZoneId = $transportZone;
    }

    $result = $this->parent->API_Call("DELETE", "/api/2.0/vdn/scopes/".$transportZoneId);

    // code 200 == deleted
    return $result['status'];
  }

}
