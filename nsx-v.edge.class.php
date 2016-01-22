<?php

namespace NSX_v_API;

class Edge extends \NSX_v_API
{
  private $parent;

  public function __construct($parent)
  {
    $this->parent = $parent;
  }

  /**
   * Get all data belonging to NSX Edges
   *
   * @param string $edgeId Optional name or identifier to return a single edge
   *
   * @return array All data on NSX Edges
   */
  public function Get($edgeId = "")
  {
    // check whether there's a specific edge requested

    // there's no API call to look for a specific edge based off a name, so
    // look for "edge-$digit" to request only the info from a specific edge
    if(!empty($edgeId) && preg_match("/^edge\-(\d+)$/", $edgeId))
      return $this->GetSpecific($edgeId);

    // if we're not looking for a specific edge or we're looking for an edge
    // based on a name, collect all edges
    $xml_str     = $this->parent->API_Call("GET", "/api/4.0/edges");
    $array_edges = $this->parent->XML_to_Array($xml_str);

    // if we're looking for a specific edge based on a name, look for
    // it and discard the rest. Return empty array if the edge isn't found
    if(!empty($edgeId) && !preg_match("/^edge\-(\d+)$/", $edgeId))
    {
      foreach($array_edges['edgePage']['edgeSummary'] as $id => $edgeInfo)
      {
        // match on name and get edge info
        if($edgeInfo['name'] == $edgeId)
          return $this->GetSpecific($edgeInfo['objectId']);
      }
      return array();
    }

    // return all edges - loop through the edge summaries and get
    // detailed info for the edges
    $edges = array();
    foreach($array_edges['edgePage']['edgeSummary'] as $id => $edgeInfo)
    {
      $objectId = $edgeInfo['objectId'];
      $edges[$objectId] = $this->GetSpecific($objectId);
    }

    return $edges;
  }

  /**
   * Get all data belonging to a specific NSX Edge identified by "edge-XX"
   *
   * @param string $edgeId Identifier to return a single edge
   *
   * @return array All data on a specific NSX Edge
   */
  public function GetSpecific($edgeId)
  {
    // check for parameter validility
    if(empty($edgeId) || !preg_match("/^edge\-(\d+)$/", $edgeId))
      return array();

    $xml_str    = $this->parent->API_Call("GET", "/api/4.0/edges/".$edgeId);
    return $this->parent->XML_to_Array($xml_str);
  }


  /**
   * Get configured NAT configuration for a specific NSX Edge
   *
   * @param string $edgeId Required name or identifier to return a single edge
   *
   * @return array NAT Configuration for NSX Edge
   */
  public function GetNATConfiguration($edgeId)
  {
    // check param
    if(empty($edgeId))
      throw new \Exception("Please supply NSX Edge name or ID as parameter.");

    // check whether there's a specific edge requested
    if(!preg_match("/^edge\-(\d+)$/", $edgeId))
    {
      // if we're not looking for a specific edge or we're looking for an edge
      // based on a name, collect all edges
      $xml_str     = $this->parent->API_Call("GET", "/api/4.0/edges");
      $array_edges = $this->parent->XML_to_Array($xml_str);

      // if we're looking for a specific edge based on a name, look for
      // it remember the edge-XX ID for use in the NAT configuration request
      $found = false;
      foreach($array_edges['edgePage']['edgeSummary'] as $id => $edgeInfo)
      {
        // match on name and get edge info
        if($edgeInfo['name'] == $edgeId) {
          $edgeId = $edgeInfo['objectId'];
          $found  = true;
          break;
        }
      }

      if(!$found)
        throw new \Exception("NSX Edge \"".$edgeId."\" not found!");
    }

    // return the NAT configuration for this edge
    $xml_str   = $this->parent->API_Call("GET", "/api/4.0/edges/".$edgeId."/nat/config");
    $array_nat = $this->parent->XML_to_Array($xml_str);

    return $array_nat;
  }


  /**
   * Get routing configuration for a specific NSX Edge
   *
   * @param string $edgeId Required name or identifier to return a single edge
   *
   * @return array Routing configuration for NSX Edge
   */
  public function GetRoutingConfiguration($edgeId)
  {
    // check param
    if(empty($edgeId))
      throw new \Exception("Please supply NSX Edge name or ID as parameter.");

    // check whether there's a specific edge requested
    if(!preg_match("/^edge\-(\d+)$/", $edgeId))
    {
      // if we're not looking for a specific edge or we're looking for an edge
      // based on a name, collect all edges
      $xml_str     = $this->parent->API_Call("GET", "/api/4.0/edges");
      $array_edges = $this->parent->XML_to_Array($xml_str);

      // if we're looking for a specific edge based on a name, look for
      // it remember the edge-XX ID for use in the NAT configuration request
      $found = false;
      foreach($array_edges['edgePage']['edgeSummary'] as $id => $edgeInfo)
      {
        // match on name and get edge info
        if($edgeInfo['name'] == $edgeId) {
          $edgeId = $edgeInfo['objectId'];
          $found  = true;
          break;
        }
      }

      if(!$found)
        throw new \Exception("NSX Edge \"".$edgeId."\" not found!");
    }

    // return the NAT configuration for this edge
    $xml_str   = $this->parent->API_Call("GET", "/api/4.0/edges/".$edgeId."/routing/config");
    $array_nat = $this->parent->XML_to_Array($xml_str);

    return $array_nat;
  }

}

?>
