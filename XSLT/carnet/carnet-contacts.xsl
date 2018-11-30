<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
				xmlns:cnt="http://www.univ-amu.fr/XML/carnet"
				xmlns:com="http://www.univ-amu.fr/XML/commun">
	<xsl:output method="html" indent="yes" version="4.0" encoding="UTF-8"/>

	<xsl:template match="/">
		<html>
			<head>
				<title>Carnet de contacts</title>
				<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
			</head>
			<body>
				<xsl:apply-templates select=".//cnt:contacts"/>
				<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"/>
    			<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"/>
    			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"/>
    			<script type="text/javascript">
    				function collapseCarnet(action)
    				{
    					console.log($('.collapse'));
    					if(action === "_afficher") {
    						$('.collapse').collapse('show');
    					} else if(action === "_masquer") {
    						$('.collapse').collapse('hide');
    					}
    				}
    			</script>
			</body>
		</html>
	</xsl:template>
	
	<xsl:template match="cnt:contacts">
		<div class="container">
			<div class="jumbotron" style="background-color: rgba(92, 125, 224, 0.5) !important;">
				<h1 class="display-4 text-primary text-center">
					Carnet de contacts
				</h1>
			</div>
			<div class="row justify-content-around" style="margin-bottom: 40px;">
				<div class="col-md-3" onclick="collapseCarnet('_afficher');">
					<button style="width: 100%;" class="btn btn-info">
						Afficher le carnet
					</button>
				</div>
				<div class="col-md-3" onclick="collapseCarnet('_masquer');">
					<button style="width: 100%;" class="btn btn-info">
						Masquer le carnet
					</button>
				</div>
			</div>
			<div class="table-responsive collapse">
				<table class="table table-bordered border-contacts">
					<thead>
						<tr class="table-info text-center">
							<th scope="col">Civilité</th>
							<th scope="col">Nom</th>
							<th scope="col">Prénom</th>
							<th scope="col">Téléphone</th>
							<th scope="col">Email</th>
							<th scope="col">Site de travail</th>
						</tr>
					</thead>
					<tbody>
						<xsl:apply-templates select="cnt:contact">
							<xsl:sort select=".//com:nom/text()" order="ascending" lang="FR" data-type="text"/>
							<xsl:sort select=".//com:prénomUsuel/text()" order="ascending" lang="FR" data-type="text"/>
						</xsl:apply-templates>
					</tbody>
				</table>
			</div>
		</div>
	</xsl:template>
	
	<xsl:template match="cnt:contact">
		<tr>
			<td><xsl:value-of select=".//com:civilité/text()"/></td>
			<td><xsl:value-of select=".//com:nom/text()"/></td>
			<td><xsl:value-of select=".//com:prénomUsuel/text()"/></td>
			<td><xsl:value-of select=".//com:numTéléphone/text()"/></td>
			<td><xsl:value-of select=".//com:adresseMél/text()"/></td>
			<td><xsl:value-of select="@siteDeTravail"/></td>
		</tr>
	</xsl:template>
</xsl:stylesheet>