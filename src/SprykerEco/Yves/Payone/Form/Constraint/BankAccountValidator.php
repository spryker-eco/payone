<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Form\Constraint;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BankAccountValidator extends ConstraintValidator
{
    public const INVALID_STATUSES = [
        PayoneApiConstants::RESPONSE_TYPE_ERROR,
        PayoneApiConstants::RESPONSE_TYPE_INVALID,
    ];

    /**
     * @param string $value
     * @param \Symfony\Component\Validator\Constraint|\SprykerEco\Yves\Payone\Form\Constraint\BankAccount $constraint
     *
     * @throws \Symfony\Component\Form\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof BankAccount) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\BankAccount');
        }

        if ($value === null || $value === '') {
            return;
        }

    /** @var \Symfony\Component\Form\Form $root */
        $root = $this->context->getRoot();

    /** @var \Generated\Shared\Transfer\QuoteTransfer $data */
        $data = $root->getData();

        $validationMessages = $this->validateBankAccount($data, $constraint);

        if (count($validationMessages) > 0) {
            foreach ($validationMessages as $validationMessage) {
                $this->buildViolation($validationMessage)
                ->addViolation();
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $data
     * @param \SprykerEco\Yves\Payone\Form\Constraint\BankAccount $constraint
     *
     * @return string[]
     */
    protected function validateBankAccount(QuoteTransfer $data, BankAccount $constraint)
    {
        $response = $constraint->getPayoneClient()->bankAccountCheck($data);
        if (in_array($response->getStatus(), static::INVALID_STATUSES)) {
            return [$response->getCustomerErrorMessage()];
        }

        return [];
    }
}
