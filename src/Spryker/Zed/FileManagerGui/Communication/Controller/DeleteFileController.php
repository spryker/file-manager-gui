<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory getFactory()
 */
class DeleteFileController extends AbstractController
{
    /**
     * @var string
     */
    protected const URL_REDIRECT_BASE = '/file-manager-gui';

    /**
     * @var string
     */
    protected const URL_PARAM_ID_FILE_INFO = 'id-file-info';

    /**
     * @var string
     */
    protected const URL_PARAM_ID_FILE = 'id-file';

    /**
     * @var string
     */
    protected const REFERER_PARAM = 'referer';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function fileInfoAction(Request $request)
    {
        $idFile = $this->castId($request->get(static::URL_PARAM_ID_FILE));
        $idFileInfo = $this->castId($request->get(static::URL_PARAM_ID_FILE_INFO));
        $redirectUrl = Url::generate(sprintf(static::URL_REDIRECT_BASE . '/edit-file?id-file=%s', $idFile))->build();

        $fileInfoVersionsCount = $this->getFactory()->getFileManagerFacade()->getFileInfoVersionsCount($idFile);
        if ($fileInfoVersionsCount <= 1) {
            $this->addErrorMessage('You cannot remove the only version of the file.');

            return $this->redirectResponse($redirectUrl);
        }

        $this->getFactory()
            ->getFileManagerFacade()
            ->deleteFileInfo($idFileInfo);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function fileAction(Request $request)
    {
        $deleteForm = $this->getFactory()->createDeleteFileForm()->handleRequest($request);
        $redirectUrl = Url::generate(static::URL_REDIRECT_BASE)->build();

        if (!$deleteForm->isSubmitted() || !$deleteForm->isValid()) {
            $this->addErrorMessage('CSRF token is not valid');

            return $this->redirectResponse($redirectUrl);
        }
        $idFile = $this->castId($request->get(static::URL_PARAM_ID_FILE));

        $this->getFactory()
            ->getFileManagerFacade()
            ->deleteFile($idFile);

        return $this->redirectResponse($redirectUrl);
    }
}
