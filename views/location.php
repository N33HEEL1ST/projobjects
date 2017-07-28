<div class="panel panel-primary">
    <!-- Default panel contents -->
    <div class="panel-heading">Sélection</div>
    <div class="panel-body">
        <?php include 'alerts.php'; ?>
        <form action="" method="get">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php $selectLocations->displayHTML(); ?>
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
    <div class="panel-heading"><strong>LOCATION</strong> <?php if ($locationObject->getId() > 0) : ?>Modification<?php else : ?>Ajout<?php endif ?></div>
    <div class="panel-body">
        <form action="" method="post">
            <input type="hidden" name="id" value="<?= $locationObject->getId() ?>">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="loc_name">Nom</label>
                        <input type="text" class="form-control" name="loc_name" id="loc_name" placeholder="Nom" value="<?= $locationObject->getName() ?>" />
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="cou_id">Pays</label>
                        <?php $selectCountries->displayHTML(); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="submit" class="btn btn-success btn-block" value="Valider" />
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <a href="?delete=<?= $locationObject->getId() ?>" class="btn btn-warning btn-block<?php if ($locationObject->getId() <= 0) : ?> disabled<?php endif; ?>" role="button" aria-disabled="true">Supprimer</a>
                </div>
            </div>
        </form>
    </div>
</div>