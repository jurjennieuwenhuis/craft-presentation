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

    /**
     * @param Presentation_PresentationModel $value
     *
     * @return true|string
     */
    public function validate($value)
    {
        $template = $value->getAttribute('template');

        if (empty($template) || (is_numeric($template) && intval($template) == 0))
        {
            return 'Template cannot be empty.';
        }

        return true;
    }

    public function onBeforeSave()
    {
        PresentationHelper::updateDefaultTemplate($this);
    }
}
