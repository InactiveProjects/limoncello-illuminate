<?php namespace Neomerx\Tests\LimoncelloIlluminate;

use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Neomerx\JsonApi\Contracts\Http\Headers\MediaTypeInterface;
use Neomerx\JsonApi\Contracts\Http\Query\QueryParametersParserInterface;
use Neomerx\LimoncelloIlluminate\Authentication\TokenCodec;
use Neomerx\LimoncelloIlluminate\Database\Models\Role;
use Neomerx\LimoncelloIlluminate\Database\Models\User;
use Neomerx\LimoncelloIlluminate\Http\Controllers\Api\Controller;
use Neomerx\LimoncelloIlluminate\Http\Requests\Request;

/** @noinspection PhpTooManyParametersInspection
 * @package Neomerx\LimoncelloIlluminate\Tests
 *
 * @method void assertResponseOk()
 * @method void assertNull($value)
 * @method void assertNotNull($value)
 * @method void assertNotEmpty($value)
 * @method void assertCount($expected, $actual)
 * @method void assertEquals($expected, $actual)
 * @method void assertGreaterThan($expected, $actual)
 * @method Response call($m, $u, array $p = [], array $c = [], array $f = [], array $s = [], $c = null)
 */
trait TestCaseTrait
{
    use DatabaseTransactions;

    /**
     * @return User
     */
    protected function admin()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $admin = User::where(User::FIELD_ID_ROLE, '=', Role::ENUM_ROLE_ADMIN_ID)->firstOrFail();

        return $admin;
    }

    /**
     * @return User
     */
    protected function user()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $admin = User::where(User::FIELD_ID_ROLE, '=', Role::ENUM_ROLE_USER_ID)->firstOrFail();

        return $admin;
    }

    /**
     * @param User|null $user
     * @param string    $subUrl
     * @param array     $parameters
     *
     * @return Response
     */
    protected function callGet(User $user = null, $subUrl = '', array $parameters = [])
    {
        return $this->makeHttpCall('GET', $user, $subUrl, $parameters);
    }

    /**
     * @param User|null $user
     * @param string    $subUrl
     * @param array     $parameters
     *
     * @return Response
     */
    protected function callDelete(User $user = null, $subUrl = '', array $parameters = [])
    {
        return $this->makeHttpCall('DELETE', $user, $subUrl, $parameters);
    }

    /**
     * @param User|null $user
     * @param string    $content
     *
     * @return Response
     */
    protected function callPost(User $user = null, $content = null)
    {
        $subUrl     = '';
        $parameters = [];
        return $this->makeHttpCall('POST', $user, $subUrl, $parameters, $content);
    }

    /**
     * @param User|null $user
     * @param string    $subUrl
     * @param string    $content
     *
     * @return Response
     */
    protected function callPatch(User $user = null, $subUrl = null, $content = null)
    {
        $parameters = [];
        return $this->makeHttpCall('PATCH', $user, $subUrl, $parameters, $content);
    }

    /**
     * @param int $number
     * @param int $size
     *
     * @return array
     */
    protected function getPageParams($number, $size)
    {
        return [
            QueryParametersParserInterface::PARAM_PAGE => [
                Request::PARAM_PAGING_SIZE   => $size,
                Request::PARAM_PAGING_NUMBER => $number,
            ],
        ];
    }

    /**
     * @param array $paths
     *
     * @return array
     */
    protected function getIncludeParams(array $paths)
    {
        $pathsList = implode(',', $paths);
        return [
            QueryParametersParserInterface::PARAM_INCLUDE => $pathsList,
        ];
    }

    /**
     * @param string      $verb
     * @param User|null   $user
     * @param string      $subUrl
     * @param array       $parameters
     * @param string|null $content
     *
     * @return Response
     */
    private function makeHttpCall($verb, User $user = null, $subUrl = '', array $parameters = [], $content = null)
    {
        /** @noinspection PhpUndefinedClassConstantInspection */
        $url = Controller::API_URL_PREFIX . '/' . static::API_SUB_URL;
        empty($subUrl) === true ?: $url .= '/' . $subUrl;

        /** @noinspection PhpUndefinedMethodInspection */
        return $this->call($verb, $url, $parameters, [], [], $this->getServerArray($user), $content);
    }

    /**
     * @param User|null $user
     *
     * @return array
     */
    private function getServerArray(User $user = null)
    {
        $server = [
            'CONTENT_TYPE' => MediaTypeInterface::JSON_API_MEDIA_TYPE,
        ];

        $headers = [
            'CONTENT-TYPE'  => MediaTypeInterface::JSON_API_MEDIA_TYPE,
            'ACCEPT'        => MediaTypeInterface::JSON_API_MEDIA_TYPE,
        ];

        if ($user !== null) {
            $headers['Authorization'] = 'Bearer ' . $this->getToken($user);
        }

        foreach ($headers as $key => $value) {
            $server['HTTP_' . $key] = $value;
        }

        return $server;
    }

    /**
     * @param User $user
     *
     * @return string
     */
    private function getToken(User $user)
    {
        return (new TokenCodec())->encode($user);
    }
}
