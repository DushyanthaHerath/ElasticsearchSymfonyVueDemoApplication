<?php


namespace App\Tests\API;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Trikoder\Bundle\OAuth2Bundle\Tests\Fixtures\FixtureFactory;
use Trikoder\Bundle\OAuth2Bundle\Tests\Fixtures\User;

final class AuthenticationTest extends TestCase
{
    public function testAuthentication(): void
    {
        self::bootKernel();

        self::$kernel->getContainer()->get('event_dispatcher')
        ->addListener('trikoder.oauth2.user_resolve', static function (UserResolveEvent $event): void {
            $event->setUser(FixtureFactory::createUser());
        });

        $client = HttpClient::create();

        timecop_freeze(new DateTimeImmutable());

        try {
            $client->request('POST', '/token', [
                'client_id' => 'foo',
                'client_secret' => 'secret',
                'grant_type' => 'password',
                'username' => 'user',
                'password' => 'pass',
            ]);
        } finally {
            timecop_return();
        }

        $response = $client->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/json; charset=UTF-8', $response->headers->get('Content-Type'));

        $jsonResponse = json_decode($response->getContent(), true);

        $this->assertSame('Bearer', $jsonResponse['token_type']);
        $this->assertSame(3600, $jsonResponse['expires_in']);
        $this->assertNotEmpty($jsonResponse['access_token']);
        $this->assertNotEmpty($jsonResponse['refresh_token']);
    }
}