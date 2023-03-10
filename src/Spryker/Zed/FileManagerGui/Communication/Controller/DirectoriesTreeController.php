<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Controller;

use Generated\Shared\Transfer\FileDirectoryTreeTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory getFactory()
 */
class DirectoriesTreeController extends AbstractController
{
    /**
     * @var string
     */
    protected const FILE_DIRECTORY_TREE = 'file-directory-tree';

    /**
     * @return array
     */
    public function indexAction()
    {
        $fileDirectoryTreeTransfer = $this->getFileDirectoryTreeTransfer();

        $fileTable = $this->getFactory()
            ->createFileTable();

        return [
            'deleteDirectoryForm' => $this->getFactory()->createDeleteDirectoryForm()->createView(),
            'files' => $fileTable->render(),
            'fileDirectoryTree' => $fileDirectoryTreeTransfer,
        ];
    }

    /**
     * @return array
     */
    public function treeAction()
    {
        $fileDirectoryTreeTransfer = $this->getFileDirectoryTreeTransfer();

        return [
            'fileDirectoryTree' => $fileDirectoryTreeTransfer,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateHierarchyAction(Request $request)
    {
        $fileDirectoryTreeData = $request->request->all(static::FILE_DIRECTORY_TREE);

        if (!$fileDirectoryTreeData) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Incorrect request data.',
            ]);
        }

        $fileDirectoryTreeTransfer = new FileDirectoryTreeTransfer();

        $fileDirectoryTreeTransfer->fromArray($fileDirectoryTreeData);

        $this->getFactory()
            ->getFileManagerFacade()
            ->updateFileDirectoryTreeHierarchy($fileDirectoryTreeTransfer);

        return $this->jsonResponse([
            'success' => true,
            'message' => 'File Directory tree updated successfully.',
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\FileDirectoryTreeTransfer
     */
    protected function getFileDirectoryTreeTransfer()
    {
        return $this->getFactory()
            ->getFileManagerFacade()
            ->findFileDirectoryTree();
    }
}
