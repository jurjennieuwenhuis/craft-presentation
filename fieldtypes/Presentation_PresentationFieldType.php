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

    public function getInputHtml($name, $value)
    {
        if (!empty($value))
        {
            $model = Presentation_PresentationModel::populateModel($value);
        }
        else
        {
            $model = new Presentation_PresentationModel();
            $model->handle = $name;
        }

        // The folder where the presentation templates are found.
        $templateLocation = $this->getSettings()->getAttribute('templateLocation');

        $templateOptionList = PresentationHelper::getTemplateOptionList($templateLocation);

        return craft()->templates->render('presentation/_fieldtypes/Presentation/input', array(
            'presentation' => $model->getAttributes(),
            'templateOptionList' => $templateOptionList,
        ));
    }

    protected function defineSettings()
    {
        return [
            'templateLocation' => [AttributeType::String],
        ];
    }

    public function getSettingsHtml()
    {
        $pluginSettings = craft()->plugins->getPlugin('presentation')->getSettings();

        $basePath = $pluginSettings->basePath;

        return craft()->templates->render('presentation/_fieldtypes/Presentation/settings', [
            'settings' => $this->getSettings(),
            'templateLocationOptions' => PresentationHelper::getTemplateLocationOptionList($basePath),
        ]);
    }
}
