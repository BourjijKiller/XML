<?php
/**
 * Si le document avec les montants déjà converti n'existe pas
 * on lance la méthode de conversion des montants du document en € afin de créer un nouveau document transformé
 */
if(!file_exists("catalogue_EURO.xml")) {
    // Chargement du fichier catalogue.xml
    $xmlFile = simplexml_load_file('catalogue.xml') or die("Error : Cannot load XML file catalogue.xml");
// Config NameSpaces
    $ns = $xmlFile->getNamespaces();
    foreach ($ns as $pre => $uri) {
        $xmlFile->registerXPathNamespace($pre, $uri);
    }
    conversionEuro($xmlFile);
}
else {
    $xmlFile = simplexml_load_file('catalogue_EURO.xml') or die("Error : Cannot load XML file catalogue_EURO.xml");
    $HTML = <<<HTML
        <h1 style="text-align: center; text-decoration: underline;">
            Catalogue de produits
        </h1>
        <div style="margin-left: 20px;">
            <h2 style="color: #FF6347; text-decoration: underline;"> Calcul du chiffre d'affaires </h2>
            <ul style="margin-left: 10px;">
HTML;
getChiffreAffaires($xmlFile, "EURO", $HTML);
getChiffreAffaires($xmlFile, "DOLLAR", $HTML);
getChiffreAffaires($xmlFile, "LIVRE", $HTML);
$HTML .= <<<HTML
    </ul>
</div>
HTML;
getCatalogue($xmlFile, $HTML);
echo $HTML;
}

/**
 * Calcul le chiffre d'affaire en fonction des paramètres saisis et l'écrit dans le buffer HTML
 * @param $xmlFile document XML
 * @param string $devise devise d'obtention du chiffre d'affaire
 * @param $HTML buffer HTML (passage par pointeur)
 */
function getChiffreAffaires($xmlFile, string $devise, &$HTML) : void
{
    $CA = 0.0;
    foreach ($xmlFile->children("http://www.univ-amu.fr/XML/catalogue", FALSE) as $familles) {
        foreach ($familles->produit as $produit) {
            $quantiteProd = $produit->xpath(".//cat:quantitéStock/text()")[0];
            foreach ($produit->xpath(".//cat:prix") as $prix) {
                foreach ($prix->xpath(".//cat:montant") as $montant) {
                    switch ($devise) {
                        case "DOLLAR":
                            $CA += $quantiteProd * ($montant * 1.1285);
                            break;
                        case "LIVRE":
                            $CA += $quantiteProd * ($montant * 0.8984);
                            break;
                        default:
                            $CA += $quantiteProd * $montant;
                            break;
                    }
                }
            }
        }
    }
    $CA > 0.0 ? writeCAInHTML($devise, $HTML, $CA) : null;
}

/**
 * Affiche le catalogue complet sous forme d'un tableau écrit dans le buffer HTML
 * @param $xmlFile fichier XML source
 * @param $HTML buffer HTML
 */
function getCatalogue($xmlFile, &$HTML) : void
{
    $HTML .= <<<HTML
         <div style="margin-left: 20px;">
            <h2 style="color: #FF6347; text-decoration: underline;"> Affichage du catalogue </h2>
            <table border="3" cellpadding="15">
                <thead style="text-align: center; font-weight: bold; background-color: #bab9b9;">
                    <td>Catégorie</td>
                    <td>Désignation</td>
                    <td>Référence</td>
                    <td>Quantité</td>
                    <td>Prix unitaire</td>
                    <td>Total</td>
                </thead>
                <tbody>
HTML;

    foreach ($xmlFile->children("http://www.univ-amu.fr/XML/catalogue", FALSE) as $familles) {
        $categorie = $familles->xpath(".//cat:nom/text()")[0];
        foreach ($familles->xpath(".//cat:produit") as $produits) {
            $reference = $produits->xpath(".//@référence")[0];
            $designation = $produits->xpath(".//cat:désignation")[0];
            $quantite = $produits->xpath(".//cat:quantitéStock")[0];
            $prixUnit = $produits->xpath(".//cat:montant")[0];
            $devise = $produits->xpath(".//cat:prix/@devise")[0];
            $HTML .= <<<HTML
                <tr style="text-align: center;">
                    <td>$categorie</td>
                    <td>$designation</td>
                    <td>$reference</td>
                    <td>$quantite</td>
HTML;
            $total = $prixUnit * $quantite;
            $prixUnit = number_format((string)$prixUnit, 2);
            $total = number_format($total, 2);
            switch ($devise) {
                case "DOLLAR US":
                    $HTML .= <<<HTML
                        <td>$prixUnit $</td>
                        <td>$total $</td>
HTML;
                    break;
                case "LIVRE STERLING":
                    $HTML .= <<<HTML
                        <td>£ $prixUnit</td>
                        <td>£ $total</td>
HTML;
                    break;
                default:
                    $HTML .= <<<HTML
                        <td>$prixUnit €</td>
                        <td>$total €</td>
HTML;
                    break;
            }
        }
    }
}

/**
 * Créé une copie du fichier XML source avec tout les montants en Euro
 * @param $xmlFile Fichier XML source
 */
function conversionEuro($xmlFile) : void
{
    foreach ($xmlFile->xpath(".//cat:prix") as $prix) {
        $montant = $prix->xpath(".//cat:montant/text()")[0];
        switch ($prix["devise"]) {
            case "DOLLAR US":
                $prix->children("http://www.univ-amu.fr/XML/catalogue", FALSE)->montant = $montant * 0.87885;
                break;
            case "LIVRE STERLING":
                $prix->children("http://www.univ-amu.fr/XML/catalogue", FALSE)->montant = $montant * 1.11885;
                break;
        }
    }
    $xmlFile->asXML("catalogue_EURO.xml");
}

/**
 * Ecrit dans le buffer le chiffre d'affaires en fonction de la devise et le renvoie à l'écran
 * @param string $devise devise du chiffre d'affaires calculées
 * @param $HTML buffer HTML
 * @param float $CAFinal chiffre d'affaires total
 */
function writeCAInHTML(string $devise, &$HTML, float $CAFinal) : void
{
    $CAFinal = number_format($CAFinal, 2);
    switch ($devise) {
        case "DOLLAR":
            $HTML .= <<<HTML
                <li>
                    Chiffre d'affaires en $devise : <b>$CAFinal $</b>
                </li>
HTML;
            break;
        case "LIVRE":
            $HTML .= <<<HTML
                <li>
                    Chiffre d'affaires en $devise : <b>£ $CAFinal</b>
                </li>
HTML;
            break;
        default:
            $HTML .= <<<HTML
                <li>
                    Chiffre d'affaires en $devise : <b>$CAFinal €</b>
                </li>
HTML;
            break;
    }
}