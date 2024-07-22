<?php

namespace Nans\Faq\Controller\Adminhtml\Question;

use Nans\Faq\Api\Repository\QuestionRepositoryInterface;
use Nans\Faq\Helper\AclNames;
use Nans\Faq\Controller\Adminhtml\AbstractDeleteAction;

class Delete extends AbstractDeleteAction
{
    /**
     * @return string
     */
    protected function _getACLName(): string
    {
        return AclNames::ACL_QUESTION_DELETE;
    }

    /**
     * @return string
     */
    protected function _getRepositoryClass(): string
    {
        return QuestionRepositoryInterface::class;
    }
}