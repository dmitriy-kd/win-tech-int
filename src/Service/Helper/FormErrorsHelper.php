<?php

namespace App\Service\Helper;

use Symfony\Component\Form\FormInterface;

class FormErrorsHelper
{
    public function prepareErrors(FormInterface $form): array
    {
        $allErrors = $this->getErrorMessages($form);
        $formattedErrors = $this->prepareFieldsErrors($allErrors);

        return $formattedErrors;
    }

    public function getErrorMessages(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors() as $key => $error) {
            $errors[$key] = $error->getMessage();
        }

        if ($form->count()) {
            foreach ($form as $child) {
                if ($child->isSubmitted() &&
                    !$child->isValid() &&
                    count($childErrors = $this->getErrorMessages($child))
                ) {
                    $errors[$child->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }

    private function prepareFieldsErrors(array $allErrors): array
    {
        $fieldErrors = [];
        if (isset($allErrors['fields'])) {
            $fieldErrors = $allErrors['fields'];
            unset($allErrors['fields']);
        }

        if (isset($allErrors['customer']['fields'])) {
            $fieldErrors = $allErrors['customer']['fields'];
            unset($allErrors['customer']);
        }

        foreach ($fieldErrors as $fieldId => $errors) {
            $fieldsErrors[$fieldId] = isset($fieldsErrors[$fieldId]) ?
                array_merge($fieldErrors[$fieldId], $errors) :
                $errors;
        }

        $allErrors = $this->toFlatArray($allErrors);

        return [
            'common' => $allErrors,
            'fields' => $fieldErrors,
        ];
    }

    protected function toFlatArray(array $array): array
    {
        $res = [];
        foreach ($array as $item) {
            if (is_array($item)) {
                $res = array_merge($res, $this->toFlatArray($item));
            } else {
                $res[] = $item;
            }
        }

        return $res;
    }

    protected function getFormFields(FormInterface $form): array
    {
        $result = [];
        foreach ($form->all() as $type) {
            array_push($result, $type->getName());
        }
        return $result;
    }

    public function prepareApiErrors(FormInterface $form): array
    {
        $errorsArr = $this->getErrorMessages($form);
        $fieldErrors = [];
        $commonErrors = [];
        $fields = $this->getFormFields($form);

        if (isset($errorsArr['pagination'])) {
            $fieldErrors['pagination'] = $this->toFlatArray($errorsArr['pagination']);
            unset($errorsArr['pagination']);
        }

        if (isset($errorsArr['filter'])) {
            $fieldErrors = array_merge($fieldErrors, $errorsArr['filter']);
            unset($errorsArr['filter']);
        }

        foreach ($errorsArr as $field => $errors) {
            if (array_search($field, $fields) !== false) {
                $fieldErrors[$field] = isset($fieldErrors[$field]) ?
                    array_merge($fieldErrors[$field], $errors) :
                    $errors;
            } else {
                $commonErrors = array_merge($commonErrors, (array) $errors);
            }
        }

        return [
            'fields' => $fieldErrors,
            'common' => $commonErrors,
        ];
    }
}