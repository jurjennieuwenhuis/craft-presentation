<?php
namespace Craft;


use Presentation\PresentationHelper;

class Presentation_PresentationFieldType extends BaseFieldType
{
    public function getName()
    {
        return Craft::t('Presentation');
    }

    public function defineContentAttribute()
    {
        return [
            AttributeType::Mixed,
            'model' => 'Presentation_PresentationModel'
        ];
    }

    /**
     * @param string $name
     * @param Presentation_PresentationModel $value
     * @return null|string
     */
    public function getInputHtml($name, $value)
    {
        // The folder where the presentation templates are found.
        $templateLocation = $this->getSettings()->getAttribute('templateLocation');

        $basePath = craft()->presentation->getPresentationBasePath();
        $templateOptionList = PresentationHelper::getTemplateOptionList($templateLocation, $basePath);

        $disableUserInput = $this->getSettings()->getAttribute('disableUserInput');

        // Hide the presentation dropdown if there is only one presentation available, or if set in the input settings
        if (count($templateOptionList) <= 1 || $disableUserInput)
        {
            return null;
        }

        if (!empty($value))
        {
            $model = Presentation_PresentationModel::populateModel($value);
        }
        else
        {
            $model = new Presentation_PresentationModel();
            $model->handle = $name;
        }

        $useDefaultTemplate = $this->getSettings()->getAttribute('useDefaultTemplate');
        $defaultTemplate = $this->getSettings()->getAttribute('defaultTemplate');

        if ($useDefaultTemplate || $disableUserInput)
        {
            if (empty($model->template) || $disableUserInput)
            {
                $model->template = $defaultTemplate;
            }
        }

        return craft()->templates->render('presentation/_fieldtypes/Presentation/input', array(
            'presentation' => $model->getAttributes(),
            'templateOptionList' => $templateOptionList,
        ));
    }

    protected function defineSettings()
    {
        return [
            'templateLocation'   => [AttributeType::String],
            'disableUserInput'   => [AttributeType::Bool],
            'useDefaultTemplate' => [AttributeType::Bool],
            'defaultTemplate'    => [AttributeType::String],
        ];
    }

    public function getSettingsHtml()
    {
        craft()->templates->includeJsResource('presentation/js/presentation.js');
        //craft()->templates->includeJs('new Craft.Presentation();');

        // The folder where the presentation templates are found.
        $templateLocation = $this->getSettings()->getAttribute('templateLocation');

        $basePath = craft()->presentation->getPresentationBasePath();
        $templateOptionList = PresentationHelper::getTemplateOptionList($templateLocation, $basePath);


        return craft()->templates->render('presentation/_fieldtypes/Presentation/settings', [
            'settings' => $this->getSettings(),
            'templateLocationOptions' => PresentationHelper::getTemplateLocationOptionList($basePath),
            'templateOptionList' => $templateOptionList,
        ]);
    }

    public function onBeforeSave()
    {
        /** @var FieldModel $field */
        $field = $this->model;

        // No need to update if field is new
        if ($field->isNew())
        {
            return;
        }

        // get new settings
        $newSettings = $this->getSettings()->getAttributes();

        // do not continue if template is empty
        if (empty($newSettings['defaultTemplate']))
        {
            return;
        }

        $fieldId = $field->id;

        $fieldRecord = FieldRecord::model()->findById($fieldId);

        // get original settings
        $settings = $fieldRecord->getAttribute('settings');

        // Diff the settings, and return the updated values
        $diff = array_diff_assoc($newSettings, $settings);

        // Conditions to update entries
        $disableUserInputActivated = (isset($diff['disableUserInput']) && $diff['disableUserInput'] == 1);
        $disableUserInputAndDefaultTemplateChanged = ($newSettings['disableUserInput'] == 1 && isset($diff['defaultTemplate']));

        // Do not continue of conditions are not met
        if (!$disableUserInputActivated && !$disableUserInputAndDefaultTemplateChanged)
        {
            return;
        }

        // Find all field instances of current field model,
        // and set the template to the default.
        $criteria = craft()->elements->getCriteria(ElementType::Entry);

        $fieldHandle = $field->getAttribute('handle');

        $criteria->search = "{$fieldHandle}:*";

        /** @var EntryModel[] $entries */
        $entries = $criteria->find();

        foreach ($entries as $entry)
        {
            /** @var Presentation_PresentationModel $presentationModel */
            $presentationModel = $entry->getContent()->getAttribute($fieldHandle);

            if ($presentationModel->template != $newSettings['defaultTemplate'])
            {
                $presentationModel->template = $newSettings['defaultTemplate'];

                $entry->getContent()->setAttribute($fieldHandle, $presentationModel);

                craft()->entries->saveEntry($entry);

            }
        }
    }
}
