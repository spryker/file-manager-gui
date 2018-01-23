<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Controller;

use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerSaveRequestTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\FileManagerGui\Communication\Form\FileForm;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory getFactory()
 */
class AddController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createFileFormDataProvider();
        $form = $this->getFactory()
            ->createFileForm($dataProvider->getData())
            ->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $saveRequestTransfer = $this->createFileManagerSaveRequestTransfer($data);

            $this->getFactory()->getFileManagerFacade()->save($saveRequestTransfer);

            $redirectUrl = Url::generate('/file-manager-gui')->build();

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'availableLocales' => $this->getFactory()->getLocaleFacade()->getLocaleCollection(),
        ]);
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\FileManagerSaveRequestTransfer
     */
    protected function createFileManagerSaveRequestTransfer(array $data)
    {
        $requestTransfer = new FileManagerSaveRequestTransfer();
        $requestTransfer->setFile($this->createFileTransfer($data));
        $requestTransfer->setFileInfo($this->createFileInfoTransfer($data));
        $requestTransfer->setContent($this->getFileContent($data));

        return $requestTransfer;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\FileInfoTransfer
     */
    protected function createFileInfoTransfer(array $data)
    {
        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile */
        $uploadedFile = $data[FileForm::FIELD_FILE_CONTENT];
        $fileInfo = new FileInfoTransfer();

        $fileInfo->setFileExtension($uploadedFile->getClientOriginalExtension());
        $fileInfo->setSize($uploadedFile->getSize());
        $fileInfo->setType($uploadedFile->getMimeType());

        return $fileInfo;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    protected function createFileTransfer(array $data)
    {
        $file = new FileTransfer();

        if ($data[FileForm::FIELD_USE_REAL_NAME]) {
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile */
            $uploadedFile = $data[FileForm::FIELD_FILE_CONTENT];
            $file->setFileName($uploadedFile->getClientOriginalName());
        } else {
            $file->setFileName($data[FileForm::FIELD_FILE_NAME]);
        }

        return $file;
    }

    /**
     * @param array $data
     *
     * @return bool|string
     */
    protected function getFileContent(array $data)
    {
        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile */
        $uploadedFile = $data[FileForm::FIELD_FILE_CONTENT];

        return file_get_contents($uploadedFile->getRealPath());
    }
}
