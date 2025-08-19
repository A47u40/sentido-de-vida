<?php
include 'admin/db.php';

$sql = "SELECT * FROM psychologists";
$result = $conn->query($sql);
$psychologists = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $row['specialties'] = json_decode($row['specialties'], true);
        $psychologists[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sentido de Vida - Consultorio de Terapia</title>
  
  
  <!-- External CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="style2.css">
  
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
    <div class="container">
      <a class="logo" href="javascript:void(0)">
        <img src="fotos/processed_image.png" alt="Sentido de Vida Logo">
        Sentido de Vida
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="#home">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#about">Sobre Nosotros</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#therapists">Nuestros Terapeutas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#contact">Contacto</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Banner/Slider Section -->
  <section id="home" class="banner-section">
    <div class="swiper mySwiper">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <div class="slide-content" >
            <h2>Bienvenidos a Sentido de Vida</h2>
            <p>Un espacio de cuidado para la sanación, el crecimiento y la transformación.</p>
            <a href="#about" class="btn btn-primary">Conoce Más</a>
          </div>
        </div>
        <div class="swiper-slide">
          <div class="slide-content">
            <h2>Tu Camino hacia el Bienestar</h2>
            <p>Servicios de terapia profesional adaptados a tus necesidades únicas.</p>
            <a href="#therapists" class="btn btn-primary">Conoce a Nuestros Terapeutas</a>
          </div>
        </div>
        <div class="swiper-slide">
          <div class="slide-content">
            <h2>Especial para Nuevos Clientes</h2>
            <p>Agenda tu primera consulta y recibe una evaluación de bienestar gratuita.</p>
            <a href="#contact" class="btn btn-primary">Contáctanos</a>
          </div>
        </div>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="about-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
          <h2 class="section-heading">Sobre Sentido de Vida</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="about-card">
            <h3>Nuestra Misión</h3>
            <p>En Sentido de Vida, creemos que todos merecen una vida plena y significativa. Nuestra misión es brindar servicios terapéuticos compasivos y efectivos que ayuden a las personas a afrontar los desafíos de la vida, sanar heridas del pasado y descubrir su capacidad innata de crecimiento y transformación.</p>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="about-card">
            <h3>Nuestro Enfoque</h3>
            <p>Adoptamos un enfoque terapéutico holístico y centrado en el cliente, reconociendo que la experiencia de cada persona es única. Nuestros terapeutas están capacitados en diversas modalidades basadas en la evidencia y colaboran con los clientes para desarrollar planes de tratamiento personalizados que aborden sus necesidades y objetivos específicos.</p>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-md-4 mb-4">
          <div class="about-card">
            <h3>Terapia Individual</h3>
            <p>Sesiones individuales centradas en el crecimiento personal, la curación y el desarrollo de estrategias de afrontamiento para los desafíos de la vida.</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="about-card">
            <h3>Terapia de Pareja</h3>
            <p>Terapia centrada en las relaciones diseñada para mejorar la comunicación, resolver conflictos y fortalecer vínculos.</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="about-card">
            <h3>Terapia Infantil</h3>
            <p>Sesiones especializadas que abordan las necesidades emocionales y de comportamiento de los niños, promueven su desarrollo saludable y brindan herramientas a padres para apoyar su bienestar.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

   <!-- Therapists Section -->
  <section id="therapists" class="therapists-section">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
          <h2 class="section-heading">Nuestros Terapeutas</h2>
          <p class="mb-5">Conozca a nuestro equipo de profesionales dedicados que están comprometidos a apoyarlo en su viaje de sanación.</p>
        </div>
      </div>

       <div class="cards-container">
        <?php foreach ($psychologists as $psy): ?>
            <div class="psychologist-card" data-psychologist="<?php echo $psy['id']; ?>">
                <div class="card-photo-wrapper">
                    <img src="admin/<?php echo htmlspecialchars($psy['photo_url']); ?>" alt="Foto de <?php echo htmlspecialchars($psy['name']); ?>">
                </div>
                <h2 class="card-name"><?php echo htmlspecialchars($psy['name']); ?></h2>
                <p class="card-career"><?php echo htmlspecialchars($psy['career']); ?></p>
                <p class="card-description"><?php echo htmlspecialchars($psy['about']); ?></p>
                <ul class="card-specialties-list">
                    <?php foreach (array_slice($psy['specialties'], 0, 3) as $specialty): ?>
                        <li><?php echo htmlspecialchars($specialty); ?></li>
                    <?php endforeach; ?>
                    <?php if (count($psy['specialties']) > 3): ?>
                        <li>...</li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>

    <?php foreach ($psychologists as $psy): ?>
    <div class="modal-overlay" id="<?php echo $psy['id']; ?>-modal">
        <div class="modal-content">
            <button class="close-button">&times;</button>
            <div class="modal-header">
                <div class="modal-photo-wrapper">
                    <img src="admin/<?php echo htmlspecialchars($psy['photo_url']); ?>" alt="Foto de <?php echo htmlspecialchars($psy['name']); ?>">
                </div>
                <h1 class="modal-name"><?php echo htmlspecialchars($psy['name']); ?></h1>
                <p class="modal-career"><?php echo htmlspecialchars($psy['career']); ?></p>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h2 class="modal-section-title"><i class="fas fa-user-circle"></i> Acerca de</h2>
                    <p class="modal-section-content"><?php echo nl2br(htmlspecialchars($psy['modal_about'])); ?></p>
                </div>
                
                <div class="modal-section">
                    <h2 class="modal-section-title"><i class="fas fa-graduation-cap"></i> Formación Académica</h2>
                    <?php 
                        $education_items = explode("\n", $psy['education']);
                        foreach ($education_items as $edu_item): 
                            $parts = explode(':', $edu_item, 2);
                            $title = isset($parts[0]) ? trim($parts[0]) : '';
                            $subtitle = isset($parts[1]) ? trim($parts[1]) : '';
                    ?>
                        <div class="modal-list-item">
                            <h3><?php echo htmlspecialchars($title); ?></h3>
                            <?php if ($subtitle): ?>
                                <p><?php echo htmlspecialchars($subtitle); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="modal-section">
                    <h2 class="modal-section-title"><i class="fas fa-star"></i> Especialidades</h2>
                    <ul class="modal-specialties-grid">
                        <?php foreach ($psy['specialties'] as $specialty): ?>
                            <li><?php echo htmlspecialchars($specialty); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="modal-section">
                    <h2 class="modal-section-title"><i class="fas fa-briefcase"></i> Experiencia</h2>
                    <p class="modal-section-content"><?php echo nl2br(htmlspecialchars($psy['experience'])); ?></p>
                </div>

                <div class="modal-section">
                    <h2 class="modal-section-title"><i class="fas fa-lightbulb"></i> Mi Enfoque Terapéutico</h2>
                    <p class="modal-section-content"><?php echo nl2br(htmlspecialchars($psy['approach'])); ?></p>
                </div>
                
                <div class="modal-section">
                    <h2 class="modal-section-title"><i class="fas fa-phone-alt"></i> Contacto</h2>
                    <div class="modal-contact-item">
                        <i class="fas fa-mobile-alt"></i> 
                        <a href="tel:<?php echo htmlspecialchars($psy['phone']); ?>"><?php echo htmlspecialchars($psy['phone']); ?></a>
                    </div>
                    <div class="modal-contact-item">
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:<?php echo htmlspecialchars($psy['email']); ?>"><?php echo htmlspecialchars($psy['email']); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>


  
</section>

<section id="appointments" class="appointments-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="section-heading">Agenda tu Cita</h2>
                    <p class="mb-5">Elige a un terapeuta y selecciona una fecha y hora disponible para tu consulta.</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="appointment-card">
                        <form id="appointment-form">
                            <div class="mb-3">
                                <label for="psychologist_select" class="form-label">Psicólogo/a</label>
                                <select class="form-control" id="psychologist_select" name="psychologist_id" required>
                                    <option value="">Selecciona un terapeuta</option>
                                    <?php foreach ($psychologists as $psy): ?>
                                        <option value="<?php echo htmlspecialchars($psy['id']); ?>">
                                            <?php echo htmlspecialchars($psy['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="appointment_date" class="form-label">Día de la Consulta</label>
                                <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="appointment_time" class="form-label">Hora Disponible</label>
                                <select class="form-control" id="appointment_time" name="appointment_time" required>
                                    <option value="">Selecciona una hora</option>
                                </select>
                                <small class="form-text text-muted">Selecciona una hora entre 8:00 AM y 6:00 PM.</small>
                            </div>
                            <div class="mb-3">
                                <label for="patient_name" class="form-label">Nombre del Paciente</label>
                                <input type="text" class="form-control" id="patient_name" name="patient_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact_number" class="form-label">Número de Contacto</label>
                                <input type="tel" class="form-control" id="contact_number" name="contact_number" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Agendar Cita</button>
                        </form>
                        <div id="form-message" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>


  <!-- Contact Section -->
  <section id="contact" class="about-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
          <h2 class="section-heading">Contáctanos</h2>
          <p class="mb-5">Estamos aquí para responder a sus preguntas y ayudarle a programar su primera cita. Contáctenos a través de cualquiera de los siguientes métodos.</p>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="about-card text-center">
            <h3>Ponte en Contacto</h3>
            <p class="mb-4">Tu camino hacia la sanación y el crecimiento está a un mensaje de distancia.</p>
            <div class="row">
              <div class="col-md-4 mb-3">
                <div class="d-flex flex-column align-items-center">
                  <i class="fas fa-map-marker-alt fa-2x mb-3" style="color: var(--primary-dark);"></i>
                  <p>123 Healing Avenue<br>Wellness City, WC 12345</p>
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <div class="d-flex flex-column align-items-center">
                  <i class="fas fa-phone fa-2x mb-3" style="color: var(--primary-dark);"></i>
                  <p>+1 (555) 123-4567</p>
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <div class="d-flex flex-column align-items-center">
                  <i class="fas fa-envelope fa-2x mb-3" style="color: var(--primary-dark);"></i>
                  <p>info@sentidodevida.com</p>
                </div>
              </div>
            </div>
            <div class="mt-4">
              <a href="javascript:void(0)" class="btn btn-primary">Agenda una Consulta</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <a class="logo mb-3 d-block" href="javascript:void(0)">
            <img src="fotos/processed_image.png" alt="Sentido de Vida Logo">
            Sentido de Vida
          </a>
          <p>Ofrecemos servicios de terapia compasiva para ayudarte a encontrar significado y propósito en tu vida.</p>
        </div>
        <div class="col-md-3">
          <h5>Enlaces Rápidos</h5>
          <ul class="list-unstyled">
            <li><a href="#home" class="text-decoration-none text-dark">Inicio</a></li>
            <li><a href="#about" class="text-decoration-none text-dark">Sobre Nosotros</a></li>
            <li><a href="#therapists" class="text-decoration-none text-dark">Nuestros Terapeutas</a></li>
            <li><a href="#contact" class="text-decoration-none text-dark">Contacto</a></li>
          </ul>
        </div>
        <div class="col-md-3">
          <h5>Síguenos</h5>
          <div class="d-flex">
            <a href="javascript:void(0)" class="text-dark me-3"><i class="fab fa-facebook-f fa-lg"></i></a>
            <a href="javascript:void(0)" class="text-dark me-3"><i class="fab fa-instagram fa-lg"></i></a>
            <a href="javascript:void(0)" class="text-dark me-3"><i class="fab fa-twitter fa-lg"></i></a>
            <a href="javascript:void(0)" class="text-dark"><i class="fab fa-linkedin-in fa-lg"></i></a>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-12 text-center">
          <p class="mb-0">&copy; 2025 Sentido de Vida. Todos los derechos reservados.</p>
        </div>
      </div>
    </div>
  </footer>

  <!-- WhatsApp Button -->
  <a href="javascript:void(0)" class="whatsapp-btn" id="whatsappBtn">
    <i class="fab fa-whatsapp"></i>
  </a>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="appointments.js"></script>
  <script src="script.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
        const cards = document.querySelectorAll('.psychologist-card');
        const modals = document.querySelectorAll('.modal-overlay');

        cards.forEach(card => {
            card.addEventListener('click', () => {
                const psychologistId = card.dataset.psychologist;
                const targetModal = document.getElementById(`${psychologistId}-modal`);
                if (targetModal) {
                    targetModal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            });
        });

        modals.forEach(modal => {
            modal.addEventListener('click', (event) => {
                if (event.target === modal || event.target.classList.contains('close-button') || event.target.closest('.close-button')) {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                modals.forEach(modal => {
                    if (modal.classList.contains('active')) {
                        modal.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
            }
        });
    });
</script>
  
</body>
</html>
