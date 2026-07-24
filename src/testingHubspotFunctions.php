<?php
// Autoload vendor files using the Composer autoloader.
// This line makes sure that all the necessary libraries and files are loaded automatically.
require_once 'vendor/autoload.php';

// Use the ApiException class from the HubSpot PHP client library.
// This class helps handle errors that might occur when interacting with the HubSpot API.
use HubSpot\Client\Crm\Contacts\ApiException;

// Use the DealsApiException class from the HubSpot PHP client library.
use HubSpot\Client\Crm\Deals\ApiException as DealsApiException;


/**
 * Retrieve deals associated with a HubSpot contact by contact ID.
 * @param \HubSpot\Client $hubspot The HubSpot client instance.
 * @param int $contactId The ID of the contact.
 * @return array|null The associated deals, or null if an error occurred.
 */
function getAssociatedDeals($hubspot, int $contactId)
{
  try {
    // Get associations for the contact
    $associations = $hubspot->crm()->associations()->v4()->basicApi()->getPage(
      'contacts',
      $contactId,
      'deals',
      100,
      null
    );

    $dealIds = array_map(function ($association) {
      return $association['to']['id'];
    }, $associations['results']);

    $deals = [];
    foreach ($dealIds as $dealId) {
      $deal = $hubspot->crm()->deals()->basicApi()->getById($dealId);
      $deals[] = $deal;
    }

    return $deals;
  } catch (DealsApiException $e) {
    echo "Error: " . $e->getMessage();
    return null;
  }
}


/**
* Fetch associated deal IDs for a given HubSpot contact ID.
* This function gets the IDs of deals associated with a specific contact.
* @param \HubSpot\Client $hubspot The HubSpot client instance.
* @param string $contactId The ID of the contact.
* @return array The associated deal IDs, or an empty array if none found.
*/
function getAssociatedDealIds($hubspot, $contactId)
{
try {
// Fetch associations (deals) for the contact.
$associations = $hubspot->crm()->associations()->v4()->basicApi()->getPage('objectType', 'objectId', 'toObjectType', 500); // Need to add the correct parameters here
$dealIds = [];
// Loop through the associations to get the deal IDs.
foreach ($associations->getResults() as $association) {
$dealIds[] = $association->getId(); // Assuming 'id' is the property name
}
return $dealIds;
} catch (ApiException $e) {
// Handle any errors that occur during the API call.
echo "Error: " . $e->getMessage();
return [];
}
}
