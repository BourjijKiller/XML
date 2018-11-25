<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
				xmlns:gui="http://www.univ-amu.fr/XML/guide"
				xmlns:com="http://www.univ-amu.fr/XML/commun">
	<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
	<xsl:template match="/">
		<html>
			<head>
				<title><xsl:value-of select="./gui:guide/gui:titre/text()"/></title>
				<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>			</head>
			<body>
				<div class="container">
					<div class="jumbotron" style="background-color: rgba(255,103,0, 0.2) !important">
						<h1 class="display-4 text-center">
							<xsl:value-of select=".//gui:titre/text()"/>
						</h1>
						<hr class="my-4"/>
						<p class="lead">
							Editeur : <xsl:value-of select=".//gui:éditeur/text()"/>
						</p>
						<p class="lead">
							Année d'édition : <xsl:value-of select=".//gui:année/text()"/>
						</p>
						<p class="lead">
							Auteur(s) :
							<xsl:for-each select=".//gui:auteur">
								<ul>
									<li>
										<p style="font-weight: bold;">
											<xsl:value-of select="com:civilité/text()"/> <xsl:text> </xsl:text> <xsl:value-of select=".//com:prénomUsuel/text()"/> <xsl:text> </xsl:text> <xsl:value-of select=".//com:nom/text()"/>
										</p>
									</li>
								</ul>
							</xsl:for-each>
						</p>
					</div>
					
					<xsl:apply-templates select=".//gui:vallon"/>
				</div>
				<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"/>
    			<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"/>
    			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"/>
    			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"/>
    			<script type="text/javascript">
			        $('a[href^="#renvoi"]').click(function() {
			        	var speed = 650;
			        	var id = $(this).attr("href");
			        	if(id === '#') {
			        		return;
			        	}
			        	$('html, body').animate({
			        		scrollTop: $(id).offset().top
			        	}, speed);
			        	return false;
			        });
    			</script>
			</body>
		</html>
	</xsl:template>
	
	<xsl:template match="gui:vallon">
		<xsl:variable name="lienVallon" select="translate(./gui:nom/text(), ' ', '-')"/>
		<xsl:variable name="idVallon" select="concat($lienVallon, '-id')"/>
		<xsl:variable name="contentVallonIntro" select="concat($lienVallon, '-tab')"/>
		<xsl:variable name="contentVallonItineraires" select="concat($lienVallon, '-itinerairesContent')"/>
		<xsl:variable name="hrefIntro" select="concat('intro-', $lienVallon)"/>
		<xsl:variable name="hrefItineraires" select="concat('iti-', $lienVallon)"/>
		<div id="accordion" style="margin-bottom: 20px;">
			<div class="card">
				<div class="card-header" id="{$idVallon}">
					<h5 class="mb-0">
						<button class="btn btn-info" data-toggle="collapse" data-target="#{$lienVallon}" aria-expanded="true" aria-controls="{$lienVallon}">
							<xsl:value-of select="gui:nom"/>
						</button>
					</h5>
				</div>
				<div id="{$lienVallon}" class="collapse show" aria-labelledby="{$idVallon}" data-parent="#accordion">
					<div class="card-body">
						<div class="card text-center">
							<div class="card">
								<div class="card-header">
									<ul class="nav nav-pills" id="myTab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" id="{$contentVallonIntro}" data-toggle="pill" href="#{$hrefIntro}" role="tab" aria-controls="{$hrefIntro}" aria-selected="true">
												Introduction
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" id="{$contentVallonItineraires}" data-toggle="pill" href="#{$hrefItineraires}" role="tab" aria-controls="{$hrefItineraires}" aria-selected="false">
												Itinéraire(s)
											</a>
										</li>
									</ul>
								</div>
								<div class="tab-content" id="myTabContent">
									<div class="tab-pane fade show active" id="{$hrefIntro}" role="tabpanel" aria-labelledby="{$contentVallonIntro}" style="margin: 20px;">
										<xsl:apply-templates select="gui:introduction/gui:paragraphe"/>
									</div>
									<div class="tab-pane fade" id="{$hrefItineraires}" role="tabpanel" aria-labelledby="{$contentVallonItineraires}" style="margin: 20px;">
										<xsl:apply-templates select="gui:itinéraire"/>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>		
			</div>
		</div>
	</xsl:template>
	
	<xsl:template match="gui:itinéraire">
		<xsl:variable name="id" select="@numéro"/>
		<div id="renvoi-{$id}">
			<h2 class="lead text-center text-info" style="font-size:30px;">
				Itinéraire n° <xsl:value-of select="@numéro"/> : <xsl:value-of select="gui:nom/text()"/>
			</h2>
		</div>
		<hr class="my-4"/>
		<div class="row">
			<div class="col-md-3">
				<p class="lead">
					<ul style="text-align: justify;">
						<li>
							Altitude : <b><xsl:value-of select="gui:altitude"/> mètres</b>
						</li>
						<li>
							Cotation : <b><xsl:value-of select="gui:cotation"/></b>
						</li>
					</ul>
				</p>
			</div>
			<div class="col-md-8">
				<p class="lead" style="font-size: 1rem; text-align: justify;">
					<xsl:apply-templates select="gui:paragraphe"/>
					<xsl:apply-templates select="gui:renvoi"/>
				</p>
			</div>
		</div>
		<div class = "row">
			<xsl:apply-templates select="gui:note"/>
		</div>
		<hr class="my-4"/>
	</xsl:template>
	
	<xsl:template match="gui:note">
		<div class="row justify-content-start">
			<div class="col-md-3 alert alert-warning">
				[<xsl:value-of select="@type"/>]
			</div>
			<div class="col-md-7 offset-md-2 alert alert-warning">
				<xsl:value-of select="."/>
			</div>
		</div>
	</xsl:template>
	
	<xsl:template match="gui:renvoi">
		<xsl:variable name="hrefRenvoi" select="@ref"/>
		<a href="#renvoi-{$hrefRenvoi}" style="text-decoration: underline; color: #2CDBF9; cursor: pointer;">
			<xsl:if test="substring($hrefRenvoi, 1, 1) = 'I'">[itinéraire lié]</xsl:if>
			<xsl:if test="substring($hrefRenvoi, 1, 1) = 'N'">[note liée]</xsl:if>
		</a>
	</xsl:template>

</xsl:stylesheet>