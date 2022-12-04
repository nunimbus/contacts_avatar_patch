<?php

namespace OCA\ContactsAvatarPatch\Middleware;

use OC\Core\Controller\ContactsMenuController;
use OCP\AppFramework\Middleware;
use OCP\AppFramework\Http\Response;
use OC;

class ContactsMenuControllerMiddleware extends Middleware {

	public function afterController($controller, $methodName, Response $response): Response {
		if (! $controller instanceof ContactsMenuController && $methodName != 'index') {
			return $response;
		}

		$userManager = OC::$server->getUserManager();
		$data = $response->getData();

		foreach ($data['contacts'] as $key=>$contact) {
			if ($userManager->get($contact->jsonSerialize()['id']) === null) {
				$data['contacts'][$key]->setAvatar("");
			}
		}

		$response->setData($data);
		return $response;
	}
}