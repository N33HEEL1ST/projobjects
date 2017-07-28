<div class="panel panel-primary">
    <!-- Default panel contents -->
    <div class="panel-heading">Sélection</div>
    <div class="panel-body">
        <?php include 'alerts.php'; ?>
        <form action="" method="get">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php $selectSessions->displayHTML(); ?>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <input type="submit" class="btn btn-success btn-block" value="Sélectionner" />
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <a href="?" class="btn btn-info btn-block">Ajouter</a>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-primary">
    <!-- Default panel contents -->
    <div class="panel-heading"><strong>SESSION</strong> <?php if ($sessionObject->getId() > 0) : ?>Modification<?php else : ?>Ajout<?php endif ?></div>
    <div class="panel-body">
        <form action="" method="post">
            <input type="hidden" name="id" value="<?= $sessionObject->getId() ?>">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label for="ses_start_date">Début</label>
                        <input type="text" class="form-control" name="ses_start_date" id="ses_start_date" placeholder="Date de début de la session (YYYY-mm-dd)" value="<?= $sessionObject->getStartDate() ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label for="ses_end_date">Fin</label>
                        <input type="text" class="form-control" name="ses_end_date" id="ses_end_date" placeholder="Date de fin de la session (YYYY-mm-dd)" value="<?= $sessionObject->getEndDate() ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label for="loc_id">Lieu</label>
                        <?php $selectLocations->displayHTML(); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="ses_start_date">Numéro de session</label>
                        <input type="text" class="form-control" name="ses_number" id="ses_number" placeholder="Numéro" value="<?= $sessionObject->getNumber() ?>">
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="tra_id">Training</label>
                        <?php $selectTrainings->displayHTML(); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="submit" class="btn btn-success btn-block" value="Valider" />
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <a href="?delete=<?= $sessionObject->getId() ?>" class="btn btn-warning btn-block<?php if ($sessionObject->getId() <= 0) : ?> disabled<?php endif; ?>" role="button" aria-disabled="true">Supprimer</a>
                </div>
            </div>
        </form>
    </div>
</div>