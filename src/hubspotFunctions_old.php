<?php
// Autoload vendor files using the Composer autoloader.
require_once 'vendor/autoload.php';

// State that we are using the ApiException class from the HubSpot PHP client library.
use HubSpot\Client\Crm\Contacts\ApiException;

/**
 * Gets all contacts and associated deals from HubSpot.
 */
function getContactsAndDeals($hubspot)
{
  $contacts = getHubSpotContactsWithHttp($hubspot);
  if (is_null($contacts)) {
    return null; // Early return if fetching contacts failed
  }

  // Assuming $contacts is an array of contact objects
  foreach ($contacts as $contact) {
    // Assuming each contact object has an 'id' property
    $contactId = $contact->id;
    $dealIds = getAssociatedDealIds($hubspot, $contactId);

    $deals = [];
    foreach ($dealIds as $dealId) {
      $dealDetails = getDealDetailsById($hubspot, $dealId);
      if (!is_null($dealDetails)) {
        $deals[] = $dealDetails;
      }
    }

    // Add the deals to the contact object. This assumes you can add properties to the contact object.
    // Adjust based on your actual data structure.
    $contact->deals = $deals;
  }

  return $contacts;
}

/**
 * Get all HubSpot contacts using the HubSpot PHP client library.
 * Pass in the HubSpot client instance.
 * @param \HubSpot\Client $hubspot The HubSpot client instance.
 * @return array|null The HubSpot contacts, or null if an error occurred.
 */
function getHubSpotContactsWithHttp($hubspot)
{
  try {
    // Assuming the correct method to fetch contacts is getAll() or similar.
    // Replace 'getAll' with the actual method name if different.
    $hubspotContacts = $hubspot->crm()->contacts()->basicApi()->getAll();

    // Finally, return the contacts.
    return $hubspotContacts;

    // Catch the exceptions that may be thrown.
  } catch (ApiException $e) {
    // If an ApiException is thrown, print the error message and return null.
    echo "Error: " . $e->getMessage();
    return null;
  } catch (InvalidArgumentException $e) {
    // If an InvalidArgumentException is thrown, print the error message and return null.
    echo "Error: " . $e->getMessage();
    return null;
  }
}

/**
 * Fetch associated deal IDs for a given HubSpot contact ID.
 * @param \HubSpot\Client $hubspot The HubSpot client instance.
 * @param string $contactId The ID of the contact.
 * @return array The associated deal IDs, or an empty array if none found.
 */
function getAssociatedDealIds($hubspot, $contactId)
{
  try {
    // Hypothetical method to fetch associations; replace with actual method
    $associations = $hubspot->crm()->contacts()->associations($contactId, 'deal')->getAll();
    $dealIds = [];
    foreach ($associations as $association) {
      $dealIds[] = $association->id; // Assuming 'id' is the property name
    }
    return $dealIds;
  } catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
    return [];
  }
}


/**
 * Fetch HubSpot deal details by deal ID.
 * @param \HubSpot\Client $hubspot The HubSpot client instance.
 * @param string $dealId The ID of the deal.
 * @return array|null The deal details, or null if an error occurred.
 */
function getDealDetailsById($hubspot, $dealId)
{
  try {
    $dealDetails = $hubspot->crm()->deals()->getById($dealId);
    return $dealDetails;
  } catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
    return null;
  }
}


/**
 * Get all HubSpot deals using the HubSpot PHP client library.
 * @param \HubSpot\Client $hubspot The HubSpot client instance.
 * @return array|null The HubSpot deals, or null if an error occurred.
 */
function getHubSpotDealsWithHttp($hubspot): ?array
{
  try {
    // Get all deals from HubSpot
    $hubspotDeals = $hubspot->crm()->deals()->basicApi()->getPageWithHttpInfo();

    // Finally, return the deals.
    return $hubspotDeals;

    // Catch the exceptions that may be thrown.
  } catch (ApiException $e) {
    // If an ApiException is thrown, print the error message and return null.
    echo "Error: " . $e->getMessage();
    return null;
  } catch (InvalidArgumentException $e) {
    // If an InvalidArgumentException is thrown, print the error message and return null.
    echo "Error: " . $e->getMessage();
    return null;
  }
}

/**
 * Get HubSpot contacts with HTTP info using the HubSpot PHP client library.
 * Pass in the HubSpot client instance and the number of contacts to retrieve.
 * @param \HubSpot\Client $hubspot The HubSpot client instance.
 * @param int $limit The number of contacts to retrieve.
 * @return array|null The HubSpot contacts with HTTP info, or null if an error occurred.
 */
function getLimitedHubSpotContactsWithHttp($hubspot, int $limit = 10): ?array
{
  try {
    // Get all contacts from HubSpot
    $hubspotContacts = $hubspot->crm()->contacts()->basicApi()->getPageWithHttpInfo(
      [
        'limit' => $limit,
      ]
    );

    // Finally, return the contacts.
    return $hubspotContacts;

    // Catch the exceptions that may be thrown.
  } catch (ApiException $e) {
    // If an ApiException is thrown, print the error message and return null.
    echo "Error: " . $e->getMessage();
    return null;
  } catch (InvalidArgumentException $e) {
    // If an InvalidArgumentException is thrown, print the error message and return null.
    echo "Error: " . $e->getMessage();
    return null;
  }
}

/**
 * Get HubSpot deals with HTTP info using the HubSpot PHP client library.
 * Pass in the HubSpot client instance and the number of deals to retrieve.
 * @param \HubSpot\Client $hubspot The HubSpot client instance.
 * @param int $limit The number of deals to retrieve.
 * @return array|null The HubSpot deals with HTTP info, or null if an error occurred.
 */
function getLimitedHubSpotDealsWithHttp($hubspot, int $limit = 10): ?array
{
  try {
    // Get all deals from HubSpot
    $hubspotDeals = $hubspot->crm()->deals()->basicApi()->getPageWithHttpInfo(
      [
        'limit' => $limit,
      ]
    );

    // Finally, return the deals.
    return $hubspotDeals;

    // Catch the exceptions that may be thrown.
  } catch (ApiException $e) {
    // If an ApiException is thrown, print the error message and return null.
    echo "Error: " . $e->getMessage();
    return null;
  } catch (InvalidArgumentException $e) {
    // If an InvalidArgumentException is thrown, print the error message and return null.
    echo "Error: " . $e->getMessage();
    return null;
  }
}

/**
 * Get the Quotes from HubSpot using the HubSpot PHP client library.
 * Pass in the HubSpot client instance and the number of quotes to retrieve.
 * @param \HubSpot\Client $hubspot The HubSpot client instance.
 * @param int $limit The number of quotes to retrieve.
 * @return array|null The HubSpot quotes with HTTP info, or null if an error occurred.
 */
function getLimitedHubSpotQuotesWithHttp($hubspot, int $limit = 10): ?array
{
  try {
    // Get all quotes from HubSpot
    $hubspotQuotes = $hubspot->crm()->quotes()->basicApi()->getPageWithHttpInfo(
      [
        'limit' => $limit,
      ]
    );

    // Finally, return the quotes.
    return $hubspotQuotes;

    // Catch the exceptions that may be thrown.
  } catch (ApiException $e) {
    // If an ApiException is thrown, print the error message and return null.
    echo "Error: " . $e->getMessage();
    return null;
  } catch (InvalidArgumentException $e) {
    // If an InvalidArgumentException is thrown, print the error message and return null.
    echo "Error: " . $e->getMessage();
    return null;
  }
}

/**
 * Search for a HubSpot contact by email using the HubSpot PHP client library.
 * Pass in the HubSpot client instance and the email address to search for.
 * @param \HubSpot\Client $hubspot The HubSpot client instance.
 * @param string $searchTerm The email address to search for.
 * @return array|null The HubSpot contacts with HTTP info, or null if an error occurred.
 */
function searchHubSpotContactByEmail($hubspot, string $searchTerm)
{
  try {
    // Create a new filter using the Filter method.
    $filter = new \HubSpot\Client\Crm\Contacts\Model\Filter();
    // Set the filter operator to 'EQ' (equals), the property name to 'email', and the value to the search term.
    $filter
      ->setOperator('EQ')
      ->setPropertyName('email')
      ->setValue($searchTerm);

    // Create a filter group with the FilterGroup method.
    $filterGroup = new \HubSpot\Client\Crm\Contacts\Model\FilterGroup();
    // Set the filter group filters to the filter created above.
    $filterGroup->setFilters([$filter]);

    // Create a search request with the PublicObjectSearchRequest method.
    $searchRequest = new \HubSpot\Client\Crm\Contacts\Model\PublicObjectSearchRequest();
    // Then set the filter groups to the filter group created above.
    $searchRequest->setFilterGroups([$filterGroup]);

    // Create a new search request with the properties 'firstname', 'lastname', 'date_of_birth', and 'email'.
    $searchRequest->setProperties(['firstname', 'lastname', 'date_of_birth', 'email']);

    // Now we can do the search using the doSearch method and store the result in the $contactsPage variable.
    $contactsPage = $hubspot->crm()->contacts()->searchApi()->doSearch($searchRequest);

    // Finally, return the result.
    return $contactsPage;

    // Catch the exceptions if anything goes wrong.
  } catch (ApiException $e) {
    // If an ApiException is thrown, print the error message and return null.
    echo "Error: " . $e->getMessage();
    return null;
  } catch (InvalidArgumentException $e) {
    // If an InvalidArgumentException is thrown, print the error message and return null.
    echo "Error: " . $e->getMessage();
    return null;
  }
}