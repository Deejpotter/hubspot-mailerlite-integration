<?php
// Autoload vendor files using the Composer autoloader.
// This line makes sure that all the necessary libraries and files are loaded automatically.
require_once 'vendor/autoload.php';

// Use the ApiException class from the HubSpot PHP client library.
// This class helps handle errors that might occur when interacting with the HubSpot API.
use HubSpot\Client\Crm\Contacts\ApiException;

/**
 * Gets all contacts and associated deals from HubSpot.
 * This function fetches contacts and their related deals from HubSpot.
 */
function getContactsAndDeals($hubspot)
{
  // Retrieve all HubSpot contacts using a helper function.
  $contacts = getHubSpotContactsWithHttp($hubspot);
  if (is_null($contacts)) {
    // If fetching contacts failed, return null early.
    return null;
  }

  // Loop through each contact to fetch their associated deals.
  foreach ($contacts as $contact) {
    // Get the contact's ID.
    $contactId = $contact->getId();
    // Retrieve associated deal IDs for the contact.
    $dealIds = getHubSpotDealsWithHttp($hubspot);

    $deals = [];
    // Loop through each deal ID to get the deal details.
    foreach ($dealIds as $dealId) {
      $dealDetails = getDealDetailsById($hubspot, $dealId);
      if (!is_null($dealDetails)) {
        // Add the deal details to the deals array.
        $deals[] = $dealDetails;
      }
    }

    // Add the deals to the contact object.
    $contact->deals = $deals;
  }

  // Return the contacts with their associated deals.
  return $contacts;
}

/**
 * Get all HubSpot contacts using the HubSpot PHP client library.
 * This function fetches all contacts from HubSpot.
 * @param \HubSpot\Client $hubspot The HubSpot client instance.
 * @return array|null The HubSpot contacts, or null if an error occurred.
 */
function getHubSpotContactsWithHttp($hubspot)
{
  try {
    // Fetch contacts with pagination.
    $hubspotContacts = $hubspot->crm()->contacts()->basicApi()->getPage();

    // Return the contacts.
    return $hubspotContacts->getResults();
  } catch (ApiException $e) {
    // Handle any errors that occur during the API call.
    echo "Error: " . $e->getMessage();
    return null;
  } catch (InvalidArgumentException $e) {
    // Handle any invalid arguments passed to the function.
    echo "Error: " . $e->getMessage();
    return null;
  }
}

/**
 * Fetch HubSpot deal details by deal ID.
 * This function gets the details of a specific deal using its ID.
 * @param \HubSpot\Client $hubspot The HubSpot client instance.
 * @param string $dealId The ID of the deal.
 * @return array|null The deal details, or null if an error occurred.
 */
function getDealDetailsById($hubspot, $dealId)
{
  try {
    // Fetch the deal details by ID.
    $dealDetails = $hubspot->crm()->deals()->basicApi()->getById($dealId);
    return $dealDetails;
  } catch (ApiException $e) {
    // Handle any errors that occur during the API call.
    echo "Error: " . $e->getMessage();
    return null;
  }
}

/**
 * Get all HubSpot deals using the HubSpot PHP client library.
 * This function fetches all deals from HubSpot.
 * @param \HubSpot\Client $hubspot The HubSpot client instance.
 * @return array|null The HubSpot deals, or null if an error occurred.
 */
function getHubSpotDealsWithHttp($hubspot): ?array
{
  try {
    // Get all deals from HubSpot.
    $hubspotDeals = $hubspot->crm()->deals()->basicApi()->getPage();

    // Return the deals.
    return $hubspotDeals->getResults();
  } catch (ApiException $e) {
    // Handle any errors that occur during the API call.
    echo "Error: " . $e->getMessage();
    return null;
  } catch (InvalidArgumentException $e) {
    // Handle any invalid arguments passed to the function.
    echo "Error: " . $e->getMessage();
    return null;
  }
}

/**
 * Get a limited number of HubSpot deals using the HubSpot PHP client library.
 * @param \HubSpot\Client $hubspot The HubSpot client instance.
 * @param int $limit The number of deals to retrieve.
 * @return array|null The HubSpot deals, or null if an error occurred.
 */
function getLimitedHubSpotDealsWithHttp($hubspot, int $limit = 10): ?array
{
  try {
    // Get a limited number of deals from HubSpot.
    $hubspotDeals = $hubspot->crm()->deals()->basicApi()->getPage($limit);

    // Return the deals.
    return $hubspotDeals->getResults();
  } catch (ApiException $e) {
    // Handle any errors that occur during the API call.
    echo "Error: " . $e->getMessage();
    return null;
  } catch (InvalidArgumentException $e) {
    // Handle any invalid arguments passed to the function.
    echo "Error: " . $e->getMessage();
    return null;
  }
}

/**
 * Get HubSpot contacts with HTTP info using the HubSpot PHP client library.
 * This function fetches a limited number of contacts from HubSpot.
 * @param \HubSpot\Client $hubspot The HubSpot client instance.
 * @param int $limit The number of contacts to retrieve.
 * @return array|null The HubSpot contacts with HTTP info, or null if an error occurred.
 */
function getLimitedHubSpotContactsWithHttp($hubspot, int $limit = 10): ?array
{
  try {
    // Get a limited number of contacts from HubSpot.
    $hubspotContacts = $hubspot->crm()->contacts()->basicApi()->getPage($limit);

    // Return the contacts.
    return $hubspotContacts->getResults();
  } catch (ApiException $e) {
    // Handle any errors that occur during the API call.
    echo "Error: " . $e->getMessage();
    return null;
  } catch (InvalidArgumentException $e) {
    // Handle any invalid arguments passed to the function.
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

