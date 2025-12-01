/* JavaScript - Gestion Salle de Sport */

/* === NAV / UI EXISTANT === */

// Hamburger menu toggle
const hamburger = document.getElementById('hamburger');
const navLinksMobile = document.getElementById('navLinksMobile');
const mobileLinks = navLinksMobile.querySelectorAll('a');

hamburger.addEventListener('click', function () {
   hamburger.classList.toggle('active');
   navLinksMobile.classList.toggle('active');
});

// Close mobile menu when a link is clicked
mobileLinks.forEach(link => {
   link.addEventListener('click', function () {
      hamburger.classList.remove('active');
      navLinksMobile.classList.remove('active');
   });
});

// Close mobile menu when scrolling
window.addEventListener('scroll', function () {
   hamburger.classList.remove('active');
   navLinksMobile.classList.remove('active');
});

// Navbar scroll effect
window.addEventListener('scroll', function () {
   const navbar = document.getElementById('navbar');
   if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
   } else {
      navbar.classList.remove('scrolled');
   }
});

// Active navigation highlighting
const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('.nav-links a');
const mobileNavLinks = document.querySelectorAll('.nav-links-mobile a');

function updateActiveNav() {
   const scrollY = window.pageYOffset;

   sections.forEach(section => {
      const sectionHeight = section.offsetHeight;
      const sectionTop = section.offsetTop - 120; // un peu de marge
      const sectionId = section.getAttribute('id');

      if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
         navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${sectionId}`) {
               link.classList.add('active');
            }
         });

         mobileNavLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${sectionId}`) {
               link.classList.add('active');
            }
         });
      }
   });
}

window.addEventListener('scroll', updateActiveNav);

// Smooth scrolling
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
   anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
         target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
         });
      }
   });
});

/* === CONTACT FORM === */

const contactForm = document.getElementById('contactForm');
if (contactForm) {
   contactForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.textContent;
      submitBtn.textContent = 'Message envoyé ✓';
      submitBtn.style.background = 'linear-gradient(135deg, #4ade80, #22c55e)';

      this.reset();

      setTimeout(() => {
         submitBtn.textContent = originalText;
         submitBtn.style.background = 'linear-gradient(135deg, #ff6b6b, #ff8e53)';
      }, 3000);
   });
}

/* =========================================================
   LOGIQUE GESTION DES COURS & ÉQUIPEMENTS (FRONT ONLY)
   ========================================================= */

// Données exemples (tu peux vider les tableaux si tu veux commencer à zéro)
let cours = [
   {
      nom: 'Yoga Matinal',
      categorie: 'Yoga',
      date: '2025-12-02',
      heure: '09:00',
      duree: 60,
      max: 15
   },
   {
      nom: 'Cardio Intensif',
      categorie: 'Cardio',
      date: '2025-12-03',
      heure: '18:30',
      duree: 45,
      max: 20
   },
   {
      nom: 'Musculation Full Body',
      categorie: 'Musculation',
      date: '2025-12-04',
      heure: '19:00',
      duree: 75,
      max: 12
   }
];

let equipements = [
   { nom: 'Tapis de course', type: 'Cardio', quantite: 5, etat: 'bon' },
   { nom: 'Vélo elliptique', type: 'Cardio', quantite: 3, etat: 'moyen' },
   { nom: 'Haltères 10kg', type: 'Musculation', quantite: 12, etat: 'bon' },
   { nom: 'Ballons de gym', type: 'Fitness', quantite: 6, etat: 'à remplacer' }
];

/* === RENDER TABLEAUX === */

function renderCoursTable() {
   const tbody = document.getElementById('coursTable');
   if (!tbody) return;

   tbody.innerHTML = '';

   cours.forEach((c, index) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
         <td>${c.nom}</td>
         <td>${c.categorie}</td>
         <td>${c.date}</td>
         <td>${c.heure}</td>
         <td>${c.duree}</td>
         <td>${c.max}</td>
         <td>
            <button class="action-btn btn-edit" onclick="editCours(${index})">✏️ Modifier</button>
            <button class="action-btn btn-delete" onclick="deleteCours(${index})">🗑️ Supprimer</button>
         </td>
      `;
      tbody.appendChild(tr);
   });
}

function renderEquipTable() {
   const tbody = document.getElementById('equipTable');
   if (!tbody) return;

   tbody.innerHTML = '';

   equipements.forEach((e, index) => {
      let etatClass = 'etat-bon';
      if (e.etat === 'moyen') etatClass = 'etat-moyen';
      if (e.etat === 'à remplacer') etatClass = 'etat-remplacer';

      const tr = document.createElement('tr');
      tr.innerHTML = `
         <td>${e.nom}</td>
         <td>${e.type}</td>
         <td>${e.quantite}</td>
         <td><span class="etat-badge ${etatClass}">${e.etat}</span></td>
         <td>
            <button class="action-btn btn-edit" onclick="editEquip(${index})">✏️ Modifier</button>
            <button class="action-btn btn-delete" onclick="deleteEquip(${index})">🗑️ Supprimer</button>
         </td>
      `;
      tbody.appendChild(tr);
   });
}

/* === MODALS COURS === */

function openAddCours() {
   const modal = document.getElementById('coursFormModal');
   document.getElementById('formCoursTitle').textContent = 'Ajouter un Cours';
   document.getElementById('coursForm').reset();
   document.getElementById('coursIndex').value = '';
   modal.style.display = 'flex';
}

function editCours(index) {
   const c = cours[index];
   const modal = document.getElementById('coursFormModal');
   document.getElementById('formCoursTitle').textContent = 'Modifier le Cours';
   document.getElementById('coursIndex').value = index;
   document.getElementById('coursNom').value = c.nom;
   document.getElementById('coursCategorie').value = c.categorie;
   document.getElementById('coursDate').value = c.date;
   document.getElementById('coursHeure').value = c.heure;
   document.getElementById('coursDuree').value = c.duree;
   document.getElementById('coursMax').value = c.max;
   modal.style.display = 'flex';
}

function closeCoursModal() {
   const modal = document.getElementById('coursFormModal');
   modal.style.display = 'none';
}

window.addEventListener('click', (e) => {
   const modalCours = document.getElementById('coursFormModal');
   if (e.target === modalCours) {
      closeCoursModal();
   }
});

/* === MODALS EQUIPEMENTS === */

function openAddEquip() {
   const modal = document.getElementById('equipFormModal');
   document.getElementById('formEquipTitle').textContent = 'Ajouter un Équipement';
   document.getElementById('equipForm').reset();
   document.getElementById('equipIndex').value = '';
   modal.style.display = 'flex';
}

function editEquip(index) {
   const e = equipements[index];
   const modal = document.getElementById('equipFormModal');
   document.getElementById('formEquipTitle').textContent = 'Modifier l’Équipement';
   document.getElementById('equipIndex').value = index;
   document.getElementById('equipNom').value = e.nom;
   document.getElementById('equipType').value = e.type;
   document.getElementById('equipQuantite').value = e.quantite;
   document.getElementById('equipEtat').value = e.etat;
   modal.style.display = 'flex';
}

function closeEquipModal() {
   const modal = document.getElementById('equipFormModal');
   modal.style.display = 'none';
}

window.addEventListener('click', (e) => {
   const modalEquip = document.getElementById('equipFormModal');
   if (e.target === modalEquip) {
      closeEquipModal();
   }
});

/* === CRUD COURS === */

const coursForm = document.getElementById('coursForm');
if (coursForm) {
   coursForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const index = document.getElementById('coursIndex').value;
      const nom = document.getElementById('coursNom').value.trim();
      const categorie = document.getElementById('coursCategorie').value.trim();
      const date = document.getElementById('coursDate').value;
      const heure = document.getElementById('coursHeure').value;
      const duree = parseInt(document.getElementById('coursDuree').value, 10);
      const max = parseInt(document.getElementById('coursMax').value, 10);

      if (!nom || !categorie || !date || !heure || !duree || !max) {
         alert('Merci de remplir tous les champs obligatoires.');
         return;
      }

      const data = { nom, categorie, date, heure, duree, max };

      if (index === '') {
         cours.push(data);
      } else {
         cours[parseInt(index, 10)] = data;
      }

      renderCoursTable();
      updateStats();
      closeCoursModal();
   });
}

function deleteCours(index) {
   if (confirm('Supprimer ce cours ?')) {
      cours.splice(index, 1);
      renderCoursTable();
      updateStats();
   }
}

/* === CRUD EQUIPEMENTS === */

const equipForm = document.getElementById('equipForm');
if (equipForm) {
   equipForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const index = document.getElementById('equipIndex').value;
      const nom = document.getElementById('equipNom').value.trim();
      const type = document.getElementById('equipType').value.trim();
      const quantite = parseInt(document.getElementById('equipQuantite').value, 10);
      const etat = document.getElementById('equipEtat').value;

      if (!nom || !type || isNaN(quantite) || !etat) {
         alert('Merci de remplir tous les champs obligatoires.');
         return;
      }

      const data = { nom, type, quantite, etat };

      if (index === '') {
         equipements.push(data);
      } else {
         equipements[parseInt(index, 10)] = data;
      }

      renderEquipTable();
      updateStats();
      closeEquipModal();
   });
}

function deleteEquip(index) {
   if (confirm('Supprimer cet équipement ?')) {
      equipements.splice(index, 1);
      renderEquipTable();
      updateStats();
   }
}

/* === DASHBOARD STATS & GRAPHIQUES === */

function updateStats() {
   const totalCoursEl = document.getElementById('totalCours');
   const totalEquipEl = document.getElementById('totalEquipements');

   if (totalCoursEl) {
      totalCoursEl.textContent = cours.length;
   }

   if (totalEquipEl) {
      const totalEquip = equipements.reduce((sum, e) => sum + (parseInt(e.quantite, 10) || 0), 0);
      totalEquipEl.textContent = totalEquip;
   }

   // Répartition des cours par catégorie
   const coursByCat = {};
   cours.forEach(c => {
      coursByCat[c.categorie] = (coursByCat[c.categorie] || 0) + 1;
   });
   drawBarChart('chartCours', coursByCat, 'Cours');

   // Répartition des équipements par état
   const etats = { bon: 0, moyen: 0, 'à remplacer': 0 };
   equipements.forEach(e => {
      if (etats[e.etat] !== undefined) {
         etats[e.etat] += e.quantite;
      }
   });
   drawBarChart('chartEquip', etats, 'Équipements');
}

function drawBarChart(canvasId, dataObj, label) {
   const canvas = document.getElementById(canvasId);
   if (!canvas) return;

   const ctx = canvas.getContext('2d');
   const width = canvas.width = canvas.offsetWidth;
   const height = canvas.height = canvas.offsetHeight;

   ctx.clearRect(0, 0, width, height);

   const labels = Object.keys(dataObj);
   const values = Object.values(dataObj);

   if (labels.length === 0) {
      ctx.fillStyle = '#888';
      ctx.font = '12px sans-serif';
      ctx.fillText('Aucune donnée', 10, height / 2);
      return;
   }

   const maxVal = Math.max(...values) || 1;
   const padding = 10;
   const barWidth = (width - padding * 2) / labels.length * 0.6;
   const barSpacing = (width - padding * 2) / labels.length;

   values.forEach((v, i) => {
      const x = padding + i * barSpacing + (barSpacing - barWidth) / 2;
      const barHeight = (v / maxVal) * (height - 30);
      const y = height - barHeight - 5;

      // Barre
      ctx.fillStyle = 'rgba(0,255,204,0.6)';
      ctx.fillRect(x, y, barWidth, barHeight);

      // Valeur
      ctx.fillStyle = '#00ffcc';
      ctx.font = '11px sans-serif';
      ctx.textAlign = 'center';
      ctx.fillText(v, x + barWidth / 2, y - 4);

      // Label
      ctx.fillStyle = '#aaa';
      ctx.font = '10px sans-serif';
      ctx.fillText(labels[i], x + barWidth / 2, height - 2);
   });
}

/* === INIT === */

window.addEventListener('DOMContentLoaded', () => {
   renderCoursTable();
   renderEquipTable();
   updateStats();
});
