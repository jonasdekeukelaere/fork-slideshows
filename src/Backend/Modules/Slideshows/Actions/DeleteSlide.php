<?php

namespace Backend\Modules\Slideshows\Actions;

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Slideshows\Engine\Model as BackendSlideshowsModel;

/**
 * This action will delete a slide
 *
 * @author Jonas De Keukelaere <jonas@sumocoders.be>
 * @author Mathias Helin <mathias@sumocoders.be>
 */
class DeleteSlide extends BackendBaseActionDelete
{
    public function execute()
    {
        $this->id = $this->getParameter('id', 'int');

        // group exists and id is not null?
        if ($this->id !== null && BackendSlideshowsModel::existsSlide($this->id)) {
            parent::execute();

            // get record
            $this->record = BackendSlideshowsModel::getSlide($this->id);

            // delete group
            BackendSlideshowsModel::deleteSlide($this->id);

            // trigger event
            BackendModel::triggerEvent($this->getModule(), 'after_delete', array('id' => $this->id));

            // item was deleted, so redirect
            $redirectURL = BackendModel::createURLForAction('Edit');
            $redirectURL .= '&id=' . $this->record['slideshow_id'];
            $redirectURL .= '&report=deleted&var=' . urlencode($this->record['title']);
            $this->redirect($redirectURL);
        } else {
            // no item found, redirect to the overview with an error
            $this->redirect(BackendModel::createURLForAction('Index') . '&error=non-existing');
        }
    }
}
