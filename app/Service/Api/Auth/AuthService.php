<?php

namespace App\Service\Api\Auth;

use App\Models\Client;
use App\Service\Api\Dto\Auth\LoginDto;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
    /**
     * @var null|\App\Models\Client
     */
    private Client|null $client;

    /**
     * @param \App\Service\Api\Dto\Auth\LoginDto $dto
     *
     * @return null|\App\Models\Client
     */
    public function login(LoginDto $dto): ?Client
    {
        $this->setClient($dto->getEmail());

        $this->checkUserExisting();
        $this->checkUserPassword(
            $dto->getPassword()
        );
        $this->checkUserVerification();

        return $this->client;
    }

    /**
     * @param string $email
     *
     * @return void
     */
    private function setClient(
        string $email
    ) {
        /* @var Client $client */
        $this->client = Client::query()
            ->where('email', '=', $email)
            ->first();
    }

    /**
     * @return void
     */
    private function checkUserExisting()
    {
        if (! $this->client) {
            abort(
                Response::HTTP_UNAUTHORIZED,
                'User does not exist'
            );
        }
    }

    /**
     * @param string $password
     *
     * @return void
     */
    private function checkUserPassword(
        string $password
    ) {
        if (! Hash::check($password, $this->client?->password)) {
            abort(
                Response::HTTP_UNAUTHORIZED,
                'Email or password incorrect'
            );
        }
    }

    /**
     * @return void
     */
    private function checkUserVerification()
    {
        if (! $this->client?->email_verified_at) {
            abort(
                Response::HTTP_FORBIDDEN,
                'Your email address is not verified.'
            );
        }
    }
}
