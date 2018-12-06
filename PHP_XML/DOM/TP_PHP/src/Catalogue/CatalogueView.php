<!--
 * Created by IntelliJ IDEA.
 * User: Samuel Entezam
 * Date: 05/12/2018
 * Time: 09:48
 */
 -->

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>
            Catalogue
        </title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <div class="jumbotron bg-warning">
                <h1 class="display-4 text-center">
                    Catalogue
                </h1>
                <hr style="margin-top: 30px;">
            </div>
            <?php if ($errors == null): ?>
                <div class="card">
                    <div class="card-header" style="background-color: rgba(116, 124, 255, 0.4);">
                        Chiffre d'affaires
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered border-contacts">
                                <thead>
                                    <tr class="table-info text-center">
                                        <th scope="col"/>
                                        <th scope="col">
                                            Devise
                                        </th>
                                        <th scope="col">
                                            Montant
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center">
                                        <th rowspan="3" style="vertical-align: middle;">
                                            Chiffre d'affaires
                                        </th>
                                        <td>Euro (€)</td>
                                        <td><?= $CAEuro ?> €</td>
                                    </tr>
                                    <tr class="text-center">
                                        <td>Dollar US ($)</td>
                                        <td><?= $CADollar ?> $</td>
                                    </tr>
                                    <tr class="text-center">
                                        <td>Livre Sterling (£)</td>
                                        <td>£ <?= $CALivre ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="row justify-content-center">
                    <?= $errors ?>
                </div>
            <?php endif; ?>
        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"/>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"/>
    </body>
</html>
