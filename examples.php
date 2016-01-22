<?php

require("nsx-v.class.php");

/**
 * Instantiate NSX_v_API object and set the login credentials for the NSX Manager
 */
$nsxv = new NSX_v_API;
$nsxv->setLoginDetails("my-nsx-manager.domain.local", "admin", "mysecretpassword");

/**
 * NSX Controller functions
 */
// Retrieve information about all NSX Controllers
var_dump($nsxv->Controller()->Get());

/**
 * NSX Edge functions
 */
// Retrieve information about all NSX Edges
var_dump($nsxv->Edge()->Get());
// Retrieve information about a NSX Edge with the ID 'edge-2'
var_dump($nsxv->Edge()->Get("edge-2"));
// Retrieve information about a NSX Edge with the name 'MyNSXEdgeFirewall'
var_dump($nsxv->Edge()->Get("MyNSXEdgeFirewall"));

// Get the NAT Configuration of a NSX Edge with the ID 'edge-3'
var_dump($nsxv->Edge()->GetNATConfiguration("edge-3"));
// Get the NAT Configuration of a NSX Edge with name 'MyNSXEdgeFirewallThatDoesNAT'
var_dump($nsxv->Edge()->GetNATConfiguration("MyNSXEdgeFirewallThatDoesNAT"));

// Get the current routing config of a NSX Edge with the ID 'edge-3'
var_dump($nsxv->Edge()->GetRoutingConfiguration("edge-3"));
// Get the current routing config of a NSX Edge with the name 'ThisIsAVirtualRouter'
var_dump($nsxv->Edge()->GetRoutingConfiguration("ThisIsAVirtualRouter"));

/**
 * NSX Logical Switch functions
 */
// Get info on all Logical Switches
var_dump($nsxv->LogicalSwitch()->Get());
// Get info on a specific Logical Switch with the ID 'virtualwire-13'
var_dump($nsxv->LogicalSwitch()->Get("virtualwire-13"));
// Get info on a specific Logical Switch with the name 'web-tier-007'
var_dump($nsxv->LogicalSwitch()->Get("web-tier-007"));
// Create a new Logical Switch with the name 'new_switch'
var_dump($nsxv->LogicalSwitch()->Create("new_switch"));
// Delete the Logical Switch named 'new_switch'
var_dump($nsxv->LogicalSwitch()->Delete("new_switch"));
// Delete the Logical Switch with the ID 'virtualwire-29'
var_dump($nsxv->LogicalSwitch()->Delete("virtualwire-29"));

/**
 * NSX Transport Zone functions
 */
// Return all info on all Transport Zones
var_dump($nsxv->TransportZone()->Get());
// Create a Transport Zone with the name 'lets_multicast' on cluster ID 'domain-c23' and set it's type to Multicast
var_dump($nsxv->TransportZone()->Create("lets_multicast", "domain-c23", "multicast"));
// Create a Transport Zone with the name 'you_and_me' on cluster ID 'domain-c23' and set it's type to Unicast
var_dump($nsxv->TransportZone()->Create("you_and_me", "domain-c23", "unicast"));
// Create a Transport Zone with the name 'all_together' on cluster ID 'domain-c23' and set it's type to Hybrid
var_dump($nsxv->TransportZone()->Create("all_together", "domain-c23", "hybrid"));
// Delete a Transport Zone with the ID 'vdnscope-2'
var_dump($nsxv->TransportZone()->Delete("vdnscope-2"));


?>
