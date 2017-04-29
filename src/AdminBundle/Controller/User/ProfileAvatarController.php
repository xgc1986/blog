<?php
declare(strict_types=1);
namespace AdminBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Xgc\CoreBundle\Controller\Controller;

class ProfileAvatarController extends Controller
{

    /**
     * @Route("/profile/avatar")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {

    }

    /**
     * @Route("/profile/avatar/update")
     * @Method({"POST"})
     */
    public function updateAction()
    {
        $file = $this->request->checkFile('avatar');

        /*
         * check filetype is image
         */

        $user = $this->getUser();
        $id = $user->getId();

        $this->get('xgc.security')->uploadAvatar($file, "/images/$id", "avatar");

        $this->addFlash(
            'notice',
            'Tu avatar ha sido actualizado correctamente'
        );

        return $this->redirectToRoute('admin_user_profile_index');
    }
}
