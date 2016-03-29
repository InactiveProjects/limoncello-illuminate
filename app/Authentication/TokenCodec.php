<?php namespace Neomerx\LimoncelloIlluminate\Authentication;

use Illuminate\Database\Eloquent\Model;
use Neomerx\Limoncello\Auth\Anonymous;
use Neomerx\Limoncello\Contracts\Auth\AccountInterface;
use Neomerx\Limoncello\Contracts\Auth\TokenCodecInterface;
use Neomerx\LimoncelloIlluminate\Database\Models\User;
use Neomerx\LimoncelloIlluminate\Logs\LoggerTrait;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class TokenCodec implements TokenCodecInterface
{
    use LoggerTrait;

    /** Token key */
    const KEY_USER_ID = User::FIELD_ID;

    /** Token key */
    const KEY_SECRET = 'secret';

    /**
     * @param Model $user
     *
     * @return string
     */
    public function encode(Model $user)
    {
        /** @var User $user */

        $secret = str_random();
        $token  = json_encode([
            self::KEY_USER_ID => $user->getKey(),
            self::KEY_SECRET  => $secret,
        ]);

        $user->{User::FIELD_API_TOKEN} = $secret;
        $user->saveOrFail();

        return $token;
    }

    /**
     * @param string $token
     *
     * @return AccountInterface
     */
    public function decode($token)
    {
        $decoded = json_decode($token, true);

        if (is_array($decoded) === true &&
            array_key_exists(self::KEY_USER_ID, $decoded) === true &&
            array_key_exists(self::KEY_SECRET, $decoded) === true
        ) {
            $token  = $decoded[self::KEY_SECRET];
            $userId = intval($decoded[self::KEY_USER_ID]);
            /** @var User $user */
            $user   = (new User())->query()->find($userId);

            if ($user !== null && $user->{User::FIELD_API_TOKEN} === $token) {
                $account = new Account($user, [Account::ATTR_ID => $userId]);

                $this->getLogger()->debug('Account authenticated.', [User::FIELD_ID => $userId]);

                return $account;
            }
        }

        return new Anonymous();
    }
}
