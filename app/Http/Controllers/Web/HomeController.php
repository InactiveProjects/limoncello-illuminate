<?php namespace Neomerx\LimoncelloIlluminate\Http\Controllers\Web;

use Illuminate\Contracts\Hashing\Hasher as HasherInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Neomerx\Limoncello\Contracts\Auth\TokenCodecInterface;
use Neomerx\LimoncelloIlluminate\Database\Models\User;
use Neomerx\LimoncelloIlluminate\Logs\LoggerTrait;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class HomeController extends Controller
{
    use LoggerTrait;

    /** Auth input param */
    const AUTH_PARAM_EMAIL = 'email';

    /** Auth input param */
    const AUTH_PARAM_PASSWORD = 'password';

    /**
     * @return string
     */
    public function index()
    {
        return 'JSON API Neomerx Demo Application';
    }

    /**
     * Issue auth token.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        $email    = $request->input(self::AUTH_PARAM_EMAIL, null);
        $password = $request->input(self::AUTH_PARAM_PASSWORD, null);

        if ($email !== null &&
            $password !== null &&
            ($user = User::query()->where(User::FIELD_EMAIL, '=', strtolower($email))->first()) !== null
        ) {
            /** @var HasherInterface $hasher */
            $hasher = app(HasherInterface::class);
            if ($hasher->check($password, $user->{User::FIELD_PASSWORD_HASH}) === true) {
                /** @var TokenCodecInterface $codec */
                $codec = app(TokenCodecInterface::class);
                $token = $codec->encode($user);

                $this->getLogger()->debug(
                    'Account login success.',
                    [User::FIELD_EMAIL => $email, User::FIELD_ID => $user->getKey()]
                );

                return response($token);
            }
        }

        $this->getLogger()->debug('Account login failed.', [User::FIELD_EMAIL => $email]);

        return response(null, Response::HTTP_UNAUTHORIZED);
    }
}
