<?php
/*
namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppointmentControllerTest extends WebTestCase
{
    
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/appointment/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /appointment/new");
        
        $crawler = $client->click($crawler->selectLink(' Añadir un schedule')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Enviar')->form(array(
            'appbundle_appointment[customer]'  => '3',
            'appbundle_appointment[appointmentDate]' => '2016-09-02',
            'appbundle_appointment[debt]' => '0',
            'appbundle_appointment[schedules][0][scheduleDate]' => '2016-09-02',
            'appbundle_appointment[schedules][0][service]' => '2',
            'appbundle_appointment[schedules][0][staff]' => '2',
            'appbundle_appointment[schedules][0][room]' => '1',
            'appbundle_appointment[schedules][0][startingHour]' => '13:00',
            'appbundle_appointment[schedules][0][finishHour]' => '13:30',
            'appbundle_appointment[schedules][0][discount]' => '0',
            'appbundle_appointment[schedules][0][price]' => '12',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

*/


/*

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Update')->form(array(
            'appbundle_appointment[field_name]'  => 'Foo',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('[value="Foo"]')->count(), 'Missing element [value="Foo"]');

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
    }

    
}

*/