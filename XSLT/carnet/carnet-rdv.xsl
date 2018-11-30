<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
				xmlns:cnt="http://www.univ-amu.fr/XML/carnet"
				xmlns:com="http://www.univ-amu.fr/XML/commun">
				
	<xsl:output method="html" version="4.0" indent="yes" encoding="UTF-8"/>
	<xsl:template match="/">
		<html>
			<head>
				<title>
					Carnet de rendez-vous
				</title>
				<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
			</head>
			<body>
				<xsl:apply-templates select=".//cnt:rendez-vous"/>
				<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"/>
    			<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"/>
    			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"/>
    			<script type="text/javascript">
    				$(document).ready(function(){
    					var divsDocument = document.getElementsByClassName('col-md-6');
    					for(var i = divsDocument.length - 1; i >= 0; i--) {
							divsDocument[i].click();
						}
    				});
    				
					function setMP(element, position, nbPx)
					{
						console.log(element);
						console.log(position);
						console.log(nbPx);
						<![CDATA[if(position > 2 && position % 2 != 0) {
							element.style.marginTop = nbPx + "px";
						}]]>
					}
				</script>
			</body>
		</html>
	</xsl:template>
	
	<xsl:template match="cnt:rendez-vous">
		<div class="container">
			<div class="jumbotron">
				<h1 class="display-4 text-primary text-center">
					Rendez-vous inscrits dans le carnet
				</h1>
			</div>
			
			<xsl:apply-templates select="cnt:rdv">
				<xsl:sort select="cnt:date" lang="FR" order="ascending" data-type="number"/>
				<xsl:sort select="cnt:heureDébut" lang="FR" order="ascending" data-type="number"/>
			</xsl:apply-templates>
		</div>
	</xsl:template>
	
	<xsl:template match="cnt:rdv">
		<div class="row">
			<div class="card bg-dark" style="width: 100%; margin-bottom: 40px;">
				<div class="card-header" style="background-color: rgba(25,117,255, 0.7) !important; color: white;">
					<h5>
						Rendez-vous le <xsl:text> </xsl:text>
						<xsl:variable name="dateRdv" select="cnt:date/text()"/>
						<xsl:value-of select="concat(substring($dateRdv, 9, 2), '/', substring($dateRdv, 6, 2), '/', substring($dateRdv,1,4))"/>
						<xsl:text> </xsl:text>
						à <xsl:value-of select="cnt:heureDébut"/>
					</h5>
				</div>
				<div class="card-body">
					<div class="row justify-content-around">
						<xsl:apply-templates select="cnt:personne"/>
					</div>
					<div class="row justify-content-center">
						<xsl:apply-templates select="com:adressePostale"/>
					</div>
				</div>
			</div>
		</div>
	</xsl:template>
	
	<xsl:template match="cnt:personne">
		<xsl:variable name="position" select="position()"/>
		<div class="col-md-6" onclick="setMP(this, '{$position}', 20)">
			<div class="card">
				<div class="card-body">
				 	<h6 class="card-subtitle text-muted text-center" style="font-size: 20px;">
					 	<xsl:value-of select="com:civilité/text()"/><xsl:text> </xsl:text><xsl:value-of select="com:prénomUsuel/text()"/><xsl:text> </xsl:text><xsl:value-of select="com:nom/text()"/>
					 </h6>
				</div>
			</div>
		</div>
	</xsl:template>
	
	<xsl:template match="com:adressePostale">
		<div class="col-md-6" style="margin-top: 20px;">
			<div class="card text-white bg-info">
				<div class="card-header lead text-center" style="background-color: rgba(9, 64, 72, 0.3) !important; font-weight: bold; font-style: italic;">
					Adresse du rendez-vous
				</div>
				<div class="card-body text-white">
					<ul>
						<li>
							<xsl:if test="boolean(com:voie/com:numéroVoie/text()) = true()">
								<xsl:value-of select="com:voie/com:numéroVoie/text()"/><xsl:text>, </xsl:text>
							</xsl:if>
							<!-- false() ???????????????? -->
							<xsl:if test="boolean(com:voie/typeVoie/text()) = false()">
								<xsl:choose>
									<xsl:when test="boolean(com:voie/com:numéroVoie/text()) = true()">
										<xsl:value-of select="com:voie/com:typeVoie/text()"/><xsl:text> </xsl:text>
									</xsl:when>
									<xsl:otherwise>
										<xsl:variable name="numVoie" select="com:voie/com:typeVoie/text()"/>
										<xsl:value-of select="concat(translate(substring($numVoie, 1, 1), 'abcdefghijklmnopqrstuvwxyz', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'), substring($numVoie, 2, string-length($numVoie)-1))"/>
										<xsl:text> </xsl:text>
									</xsl:otherwise>
								</xsl:choose>
							</xsl:if>
							<xsl:if test="boolean(com:voie/com:nom/text()) = true()">
								<xsl:value-of select="com:voie/com:nom/text()"/>
							</xsl:if>
						</li>
						<xsl:if test="boolean(com:complément/text()) = true()">
							<li>
								<b>Complément d'adresse : </b> <xsl:value-of select="com:complément/text()"/>
							</li>
						</xsl:if>
						<li>
							<b>
								<xsl:if test="boolean(com:localité/com:codePostal/text()) = true()">
									<xsl:value-of select="com:localité/com:codePostal/text()"/><xsl:text> </xsl:text>
								</xsl:if>
								<xsl:if test="boolean(com:localité/com:commune/text()) = true()">
									<xsl:value-of select="com:localité/com:commune/text()"/><xsl:text> </xsl:text>
								</xsl:if>
								<xsl:if test="boolean(com:localité/com:CEDEX/text()) = true()">
									<xsl:value-of select="com:localité/com:CEDEX/text()"/>
								</xsl:if>
							</b>
							<xsl:if test="com:pays/text()">
								<xsl:text>, </xsl:text><xsl:value-of select="com:pays/text()"/>
							</xsl:if>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</xsl:template>
</xsl:stylesheet>