<?php

namespace Backend\Modules\Slideshows\Actions;

use Backend\Core\Engine\Base\ActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Slideshows\Engine\Model;

/**
 * This action will delete a slide
 *
 * @author Jonas De Keukelaere <jonas@sumocoders.be>
 * @author Mathias Helin <mathias@sumocoders.be>
 */
class DeleteSlide extends ActionDelete
{
    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();

        $this->id = $this->getParameter('id', 'int');

        // get record
        $this->record = Model::getSlide($this->id);

        if (empty($this->record)) {
            if (empty($this->record)) {
                $redirectURL = BackendModel::createURLForAction(
                    'Index',
                    null,
                    null,
                    array(
                        'error' => 'non-existing',
                    )
                );
                $this->redirect($redirectURL);
            }
        }

        // delete slide
        Model::deleteSlide($this->id);

        // item was deleted, so redirect
        $redirectURL = BackendModel::createURLForAction(
            'Edit',
            null,
            null,
            array(
                'report' => 'deleted',
                'var' => urlencode($this->record['title']),
                'id' => $this->record['slideshow_id'],
            )
        );
        $this->redirect($redirectURL);
    }
}
