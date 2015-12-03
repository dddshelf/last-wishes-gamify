<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Gamify\Gamification\Command\RewardUserCommand;
use Gamify\Gamification\Command\SignupCommand;
use Hateoas\Representation\CollectionRepresentation;
use HttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ONGR\ElasticsearchDSL\Query\MatchAllQuery;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UsersController extends FOSRestController
{
    /**
     * Finds all the available users
     *
     * @ApiDoc(
     *      resource = true,
     *      description = "Finds all available users",
     *      statusCodes = {
     *          200 = "Returned along with the users list"
     *      }
     * )
     */
    public function getUsersAction()
    {
        $repo = $this->get('es.manager.default.user');
        $search = $repo->createSearch();
        $search->addQuery(new MatchAllQuery());

        $results = $repo->execute($search);

        return new CollectionRepresentation($results, 'users', 'users');
    }

    /**
     * Finds a user given a user ID.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Finds a user given a user ID",
     *  statusCodes={
     *      200 = "Returned when the user have been found",
     *      404 = "Returned when the user could not be found"
     *  }
     * )
     *
     * @Rest\View(
     *  statusCode = 200
     * )
     */
    public function getUserAction($id)
    {
        $repo = $this->get('es.manager.default.user');

        $user = $repo->find($id);

        if (null === $user) {
            throw $this->createNotFoundException(sprintf('A user with an ID of %s does not exist', $id));
        }

        return $user;
    }

    /**
     * Creates a new disposable URI with the new user ID
     *
     * @ApiDoc(
     *     resource = true,
     *     description = "Creates a new disposable URI with the new user ID",
     *     statusCodes = {
     *         201 = "When the virtual URI has been created successfully"
     *     }
     * )
     */
    public function postUserAction()
    {
        $anUuid = $this->get('user_repository')->nextIdentity();
        $signature = $this->createSignature($anUuid);

        $view = View::create();

        $signedId = $anUuid . '_' . $signature;

        $aVirtualUrl = $this->generateUrl('put_user', ['id' => $signedId], UrlGeneratorInterface::ABSOLUTE_URL);

        return
            $view
                ->setData(['id' => $signedId])
                ->setStatusCode(Codes::HTTP_CREATED)
                ->setHeaders([
                    'Location' => $aVirtualUrl
                ])
            ;
    }

    /**
     * Signs up a new User
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Signs up a new User",
     *  statusCodes={
     *      202 = "Returned when the request has been fulfilled successfully"
     *  }
     * )
     */
    public function putUserAction($id)
    {
        $this->assertIdHasSignature($id);

        list($userId, $aSignature) = explode('_', $id);

        $this->assertSignedIdIsValid($userId, $aSignature);

        $this->get('tactician.commandbus')->handle(new SignupCommand($userId));

        $view = View::create();

        return
            $view
                ->setStatusCode(Codes::HTTP_ACCEPTED)
                ->setLocation(
                    $this->generateUrl('get_user', ['id' => $userId], UrlGeneratorInterface::ABSOLUTE_URL)
                )
            ;
    }

    /**
     * Rewards a user with a given number of points
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Rewards a user with a given number of points",
     *  statusCodes = {
     *      202 = "Returned when the request has been fulfilled successfully"
     *  }
     * )
     *
     * @Rest\Put("/user/{id}/reward")
     * @Rest\RequestParam(name="points", requirements="\d+", description="The number of points that reward the user")
     */
    public function rewardUserAction($id, Request $request)
    {
        $this->get('tactician.commandbus')->handle(new RewardUserCommand($id, $request->request->get('points')));

        $view = View::create();

        return
            $view
                ->setStatusCode(Codes::HTTP_ACCEPTED)
                ->setLocation(
                    $this->generateUrl('get_user', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL)
                )
        ;
    }

    private function createSignature($anUuid)
    {
        return base64_encode(md5($anUuid . $this->container->getParameter('secret')));
    }

    private function assertIdHasSignature($signedId)
    {
        if (false === strpos($signedId, '_')) {
            throw new HttpException(400, 'The provided ID is not signed!');
        }
    }

    private function assertSignedIdIsValid($anId, $aComingSignature)
    {
        if (!Uuid::isValid($anId)) {
            throw new HttpException(400, 'The provided ID is not valid!');
        }

        $aSignature = $this->createSignature($anId);

        if ($aSignature !== $aComingSignature) {
            throw new HttpException(403, 'The provided ID is not valid!');
        }
    }
}