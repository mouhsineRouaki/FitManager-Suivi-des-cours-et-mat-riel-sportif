<?php 
    require "../php/config.php";
    require_once "../php/functionsEquipements.php";
    require_once "../php/functionsCour.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Salle de Sport - Dashboard</title>
    <link rel="stylesheet" href="../css/templatemo-graph-page.css">
</head>
<body>
    <nav id="navbar">
        <div class="nav-container">
            <a href="#home" class="logo">
                <div class="logo-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 13h2v8H3zm4-8h2v13H7zm4-2h2v15h-2zm4 4h2v11h-2zm4-2h2v13h-2z"/>
                    </svg>
                </div>
                <span class="logo-text">Salle de Sport</span>
            </a>
            <ul class="nav-links">
                <li><a href="#home" class="active">Accueil</a></li>
                <li><a href="#dashboard">Dashboard</a></li>
                <li><a href="./cours.php">Cours</a></li>
                <li><a href="./equipements.php">Équipements</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <a href="https://www.google.com/search" target="_blank" rel="noopener" title="Recherche">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
            </a>
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <ul class="nav-links-mobile" id="navLinksMobile">
            <li><a href="#home" class="active">Accueil</a></li>
            <li><a href="#dashboard">Dashboard</a></li>
            <li><a href="#cours">Cours</a></li>
            <li><a href="#equipements">Équipements</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-bg"></div>
        <div class="geometric-shapes">
            <div class="shape shape1"></div>
            <div class="shape shape2"></div>
            <div class="shape shape3"></div>
            <div class="shape shape4"></div>
            <div class="shape shape5"></div>
            <div class="shape shape6"></div>
        </div>
        
        <div class="hero-content">
            <div class="hero-text">
                <h1>Gestion<br>Salle de Sport</h1>
                <p>Gérez vos cours, vos coachs et vos équipements dans un seul tableau de bord moderne : planning, suivi d’occupation et état du matériel.</p>
                <a href="#dashboard" class="cta-button">Voir le Dashboard</a>
            </div>
            
            <div class="hero-visual">
                <div class="city-container">
                    <div class="building building1">
                        <div class="building-fill"></div>
                        <div class="building-windows"></div>
                    </div>
                    <div class="building building2">
                        <div class="building-fill"></div>
                        <div class="building-windows"></div>
                    </div>
                    <div class="building building3">
                        <div class="building-fill"></div>
                        <div class="building-windows"></div>
                    </div>
                    <div class="building building4">
                        <div class="building-fill"></div>
                        <div class="building-windows"></div>
                    </div>
                    <div class="neon-line neon-line1"></div>
                    <div class="neon-line neon-line2"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dashboard Section -->
    <section class="dashboard-section" id="dashboard">
        <div class="dashboard-container">
            <h2 class="section-title">Dashboard Général</h2>
            <!-- Stats Cards -->
            <div class="stats-grid">

                <div class="stat-card" onclick="passerAuPageCours()">
                    <div class="stat-header">
                        <div class="stat-icon">📚</div>
                        <div class="stat-title">Total des Cours</div>
                    </div>
                    <div class="stat-value" id="totalCours">
                        <?php totalCours();?>
                    </div>
                    <div class="stat-description">Nombre total de cours programmés dans la salle.</div>
                </div>

                <div class="stat-card" onclick="passerAuPageEquipements()">
                    <div class="stat-header">
                        <div class="stat-icon">🏋️</div>
                        <div class="stat-title">Équipements Disponibles</div>
                    </div>
                    <div class="stat-value" id="totalEquipements">
                        <?php totalEquipement(); ?>
                    </div>
                    <div class="stat-description">Quantité totale d’équipements disponibles.</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">📊</div>
                        <div class="stat-title">Répartition des Cours</div>
                    </div>
                    <div class="stat-description">Nombre de cours par catégorie (Yoga, Cardio, Musculation...)</div>
                    <div class="stat-chart">
                            <?php $statsCours = getRepartitionCoursParCategorie();
                                foreach ($statsCours as $row) {
                                    echo '<p class="stat-title">'.$row['cour_category'].' : '.$row['total'].' cours </p>';
                                }
                            ?>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">⚙️</div>
                        <div class="stat-title">État des Équipements</div>
                    </div>
                    <div class="stat-description">Répartition des équipements par état : bon, moyen, à remplacer.</div>
                    <div class="stat-chart">
                        <?php $statsEquipement = getRepartitionEquipementsParEtat();
                                foreach ($statsEquipement as $row) {
                                    echo '<p class="stat-title">'.$row['equipement_etat'].' : '.$row['total'].' equipements </p>';
                                }
                            ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="contact-section" id="cours">
        <div class="dashboard-container">
            <h2 class="section-title">cours cardio</h2>
            <div class="cours-grid" id="coursGrid">
                <?php getCoursParCategory("cardio"); ?>
            </div>
            <h2 class="section-title">cours Musculation</h2>
            <div class="cours-grid" id="coursGrid">
                <?php getCoursParCategory("Musculation"); ?>
            </div>
            <h2 class="section-title">cours Récupération</h2>
            <div class="cours-grid" id="coursGrid">
                <?php getCoursParCategory("Récupération"); ?>
            </div>
            
    </section>
    <section class="contact-section" id="equipements">
        <div class="dashboard-container">
            <h2 class="section-title">bon equipements</h2>
            <div class="cours-grid" id="coursGrid">
                <?php getEquipementParEtat("bon"); ?>
            </div>
            <h2 class="section-title">moyene equipement</h2>
            <div class="cours-grid" id="coursGrid">
                <?php getEquipementParEtat("moyenne"); ?>
            </div>
            <h2 class="section-title">faible equipement</h2>
            <div class="cours-grid" id="coursGrid">
                <?php getEquipementParEtat("faible"); ?>
            </div>
            
    </section>
    <section class="contact-section" id="contact">
        <div class="dashboard-container">
            <h2 class="section-title">Contact</h2>
            <div class="contact-grid">
                <!-- Contact Form -->
                <div class="contact-form">
                    <h3 style="margin-bottom: 30px; font-size: 24px;">Envoyez-nous un message</h3>
                    <form id="contactForm">
                        <div class="form-group">
                            <label for="name">Nom complet</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Adresse e-mail</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Sujet</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" required placeholder="Expliquez votre demande..."></textarea>
                        </div>
                        <button type="submit" class="cta-button" style="width: 100%;">Envoyer</button>
                    </form>
                </div>

                <!-- Contact Info -->
                <div class="contact-info">
                    <h3>Informations de contact</h3>
                    
                    <div class="contact-item">
                        <div class="contact-icon">📧</div>
                        <div class="contact-details">
                            <h4>Email</h4>
                            <p>contact@salledesport.com<br>support@salledesport.com</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">📞</div>
                        <div class="contact-details">
                            <h4>Téléphone</h4>
                            <p>+212 6 00 00 00 00<br>Disponible 7j/7</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">📍</div>
                        <div class="contact-details">
                            <h4>Adresse</h4>
                            <p>Rue de la Forme, Résidence Fitness<br>Casablanca, Maroc</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">🕒</div>
                        <div class="contact-details">
                            <h4>Horaires</h4>
                            <p>Lundi - Dimanche : 6h00 - 23h00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modals Cours -->
    <div id="coursFormModal" class="modal">
        <div class="modal-content">
            <h3 id="formCoursTitle">Ajouter un Cours</h3>
            <form id="coursForm">
                <input type="hidden" id="coursIndex">
                <div class="form-group">
                    <label>Nom du cours *</label>
                    <input type="text" id="coursNom" required>
                </div>
                <div class="form-group">
                    <label>Catégorie *</label>
                    <input type="text" id="coursCategorie" placeholder="Yoga, Cardio, Musculation..." required>
                </div>
                <div class="form-group">
                    <label>Date *</label>
                    <input type="date" id="coursDate" required>
                </div>
                <div class="form-group">
                    <label>Heure *</label>
                    <input type="time" id="coursHeure" required>
                </div>
                <div class="form-group">
                    <label>Durée (minutes) *</label>
                    <input type="number" id="coursDuree" required min="10">
                </div>
                <div class="form-group">
                    <label>Nombre maximum de participants *</label>
                    <input type="number" id="coursMax" required min="1">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeCoursModal()">Annuler</button>
                    <button type="submit" class="cta-button">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modals Equipements -->
    <div id="equipFormModal" class="modal">
        <div class="modal-content">
            <h3 id="formEquipTitle">Ajouter un Équipement</h3>
            <form id="equipForm">
                <input type="hidden" id="equipIndex">
                <div class="form-group">
                    <label>Nom de l’équipement *</label>
                    <input type="text" id="equipNom" required>
                </div>
                <div class="form-group">
                    <label>Type *</label>
                    <input type="text" id="equipType" placeholder="Tapis de course, Haltères..." required>
                </div>
                <div class="form-group">
                    <label>Quantité disponible *</label>
                    <input type="number" id="equipQuantite" required min="0">
                </div>
                <div class="form-group">
                    <label>État *</label>
                    <select id="equipEtat" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="bon">Bon</option>
                        <option value="moyen">Moyen</option>
                        <option value="à remplacer">À remplacer</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeEquipModal()">Annuler</button>
                    <button type="submit" class="cta-button">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
    

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p class="copyright">© 2026 Salle de Sport. Gestion des cours et équipements.</p>
        </div>
    </footer>

    <script src="../js/inscription.js"></script>

</body>
</html>
