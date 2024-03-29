<?php
// Autoload vendor files using the Composer autoloader.
require_once 'vendor/autoload.php';

// State that we are using the ApiException class from the HubSpot PHP client library.
use HubSpot\Client\Crm\Contacts\ApiException;

/**
 * Get HubSpot contacts with HTTP info using the HubSpot PHP client library.
 * Pass in the HubSpot client instance and the number of contacts to retrieve.
 * @param \HubSpot\Client $hubspot The HubSpot client instance.
 * @param int $limit The number of contacts to retrieve.
 * @return array|null The HubSpot contacts with HTTP info, or null if an error occurred.
 */
function getHubSpotContactsWithHttp($hubspot, int $limit = 10)
{
  try {
    // Get all contacts from HubSpot
    $hubspotContacts = $hubspot->crm()->contacts()->basicApi()->getPageWithHttpInfo(
      [
        'limit' => $limit,
      ]
    );
    // Output the contacts to the console.
    var_dump($hubspotContacts);

    // Finally, return the contacts.
    return $hubspotContacts;
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

    // We can output the result to the console.
    var_dump($contactsPage);
    // and finally, return the result.
    return $contactsPage;
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