<!--
 * Created by IntelliJ IDEA.
 * User: Samuel Entezam
 * Date: 10/12/2018
 * Time: 09:35
 */
 -->

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <title>
            Formation MIAGE
        </title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <div class="jumbotron" style="background-color: rgba(76, 146, 244, 0.8);">
                <h1 class="display-4 text-center text-white">
                    Les formations en MIAGE
                </h1>
                <hr class="mt-5"/>
            </div>
            <?php if($errors === null) : ?>
                <div class="row justify-content-center">
                    <?= $success[0] ?>
                </div>
                <div id="accordion">
                    <div class="card mb-5">
                        <div class="card-header" id="cardFormations">
                            <h5 class="row justify-content-around">
                                <?php foreach ($formations as $formation) :
                                    $linkFormation = $formation->getNiveau().'-'.str_replace(' ', '-', $formation->getIntitule());
                                    if(strpos($linkFormation, ':')) :
                                        $linkFormation = substr_replace($linkFormation, '', strpos($linkFormation, ':')-1);
                                    endif; ?>
                                    <button class="btn btn-info my-3 card-title" data-toggle="collapse" data-target="#<?= $linkFormation ?>" aria-expanded="true" aria-controls="<?= $linkFormation ?>">
                                        <?= $formation->getIntitule() ?>
                                    </button>
                                <?endforeach; ?>
                            </h5>
                        </div>
                        <?php foreach ($formations as $formation) :
                            $i = 0;
                            $linkFormation = $formation->getNiveau().'-'.str_replace(' ', '-', $formation->getIntitule());
                            if(strpos($linkFormation, ':')) :
                                $linkFormation = substr_replace($linkFormation, '', strpos($linkFormation, ':')-1);
                            endif; ?>
                            <div id="<?= $linkFormation ?>" class="collapse hide" aria-labelledby="cardFormations" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="card">
                                        <div class="card-header mb-3">
                                            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                                <?php foreach ($formation->getSemestre() as $semestre) :
                                                    $linkSemestre = $semestre->getNumero().'-'.str_replace(' ', '-', $formation->getIntitule());
                                                    if(strpos($linkSemestre, ':')) :
                                                        $linkSemestre = substr_replace($linkSemestre, '', strpos($linkSemestre, ':')-1);
                                                    endif;
                                                    if ($i === 0) : ?>
                                                        <li class="nav-item">
                                                            <a class="nav-link active ml-3" id="<?= $linkSemestre ?>-content" data-toggle="pill" href="#<?= $linkSemestre ?>-link" role="tab" aria-controls="<?= $linkSemestre ?>" aria-selected="true">
                                                                <?= $semestre->getNumero() ?>
                                                            </a>
                                                        </li>
                                                    <?php else : ?>
                                                        <li class="nav-item">
                                                            <a class="nav-link ml-3" id="<?= $linkSemestre ?>-content" data-toggle="pill" href="#<?= $linkSemestre ?>-link" role="tab" aria-controls="<?= $linkSemestre ?>" aria-selected="false">
                                                                <?= $semestre->getNumero() ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                <?php  $i++;
                                                endforeach;
                                                $i = 0; ?>
                                            </ul>
                                        </div>
                                        <div class="tab-content" id="pills-tabContent">
                                            <?php foreach ($formation->getSemestre() as $semestre) :
                                                $UEOption = false;
                                                $linkSemestre = $semestre->getNumero().'-'.str_replace(' ', '-', $formation->getIntitule());
                                                if(strpos($linkSemestre, ':')) :
                                                    $linkSemestre = substr_replace($linkSemestre, '', strpos($linkSemestre, ':')-1);
                                                endif;
                                                if($i === 0) : ?>
                                                    <div class="tab-pane fade show active" id="<?= $linkSemestre ?>-link" role="tabpanel" aria-labelledby="<?= $linkSemestre ?>-content">
                                                <?php else : ?>
                                                    <div class="tab-pane fade" id="<?= $linkSemestre ?>-link" role="tabpanel" aria-labelledby="<?= $linkSemestre ?>-content">
                                                <?php endif; ?>
                                                     <p class="lead text-center mt-2" style="font-size: x-large; font-weight: bold; font-style: italic; text-decoration: underline">
                                                         UE Obligatoire
                                                     </p>
                                                     <div class="table-responsive-md">
                                                         <table class="table table-bordered table-striped">
                                                             <thead class="thead-dark text-center">
                                                             <tr>
                                                                 <th scope="col">Intitulé de l'UE</th>
                                                                 <th scope="col">Module</th>
                                                                 <th scope="col">ECTS</th>
                                                                 <th scope="col">CM</th>
                                                                 <th scope="col">TD/TP</th>
                                                             </tr>
                                                             </thead>
                                                             <tbody>
                                                                <?php foreach ($semestre->getUE() as $UE) :
                                                                    if ($UE->getType() === "obligatoire") : ?>
                                                                            <?php foreach ($UE->getModule() as $module) : ?>
                                                                                <tr class="text-center">
                                                                                    <td><?= $UE->getIntitule() ?></td>
                                                                                    <td><?= $module->getIntitule() ?></td>
                                                                                    <td><?= $UE->getECTS() ?></td>
                                                                                    <td><?= $module->getHeuresCM() ?></td>
                                                                                    <td><?= $module->getHeuresTDTP() ?></td>
                                                                                </tr>
                                                                            <?php endforeach; ?>
                                                                    <?php elseif($UE->getType() === "optionnelle") :
                                                                        $UEOption = true;
                                                                    endif;
                                                                endforeach; ?>
                                                             </tbody>
                                                        </table>
                                                    </div>
                                                    <?php if($UEOption) : ?>
                                                        <p class="lead text-center mt-2" style="font-size: x-large; font-weight: bold; font-style: italic; text-decoration: underline">
                                                            UE Optionnelle
                                                        </p>
                                                        <div class="table-responsive-md">
                                                            <table class="table table-bordered table-striped">
                                                                <thead class="thead-dark text-center">
                                                                <tr>
                                                                    <th scope="col">Intitulé de l'UE</th>
                                                                    <th scope="col">Module</th>
                                                                    <th scope="col">ECTS</th>
                                                                    <th scope="col">CM</th>
                                                                    <th scope="col">TD/TP</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($semestre->getUE() as $UE) :
                                                                        if ($UE->getType() === "optionnelle") : ?>
                                                                            <!-- TODO remplissage tableau options -->
                                                                            <tr class="text-center">
                                                                                <td><?= $UE->getIntitule() ?></td>
                                                                                <td><?= $module->getIntitule() ?></td>
                                                                                <td><?= $UE->getECTS() ?></td>
                                                                                <td><?= $module->getHeuresCM() ?></td>
                                                                                <td><?= $module->getHeuresTDTP() ?></td>
                                                                            </tr>
                                                                        <?php endif;
                                                                    endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php
                                            $i++;
                                            endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else:
                foreach ($errors as $error) : ?>
                    <div class="row justify-content-center">
                        <?= $error ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>
