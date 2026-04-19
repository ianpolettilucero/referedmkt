-- =============================================================================
-- Seed de sitio demo: un sitio "demo" con 3 productos y 3 articulos para
-- validar end-to-end (home, producto, articulo, categoria, tracking).
--
-- IMPORTANTE: para que el resolver lo encuentre en dev local, exportar
-- DEV_SITE_DOMAIN=demo.localhost en .env, o apuntar /etc/hosts a un dominio
-- y registrar el dominio real.
-- =============================================================================

INSERT IGNORE INTO sites (
    domain, name, slug, theme_name, primary_color,
    affiliate_disclosure_text, default_language, default_country,
    meta_title_template, meta_description_template, active
) VALUES (
    'demo.localhost',
    'Demo Afiliados',
    'demo',
    'default',
    '#2b6cb0',
    'Divulgación: este sitio contiene enlaces de afiliados. Podemos recibir una comisión si compras a través de nuestros enlaces, sin costo adicional para vos. Esto no influye en nuestras opiniones.',
    'es',
    'AR',
    '{title} | Demo Afiliados',
    'Directorio y análisis independiente de productos recomendados para PyMEs y profesionales.',
    1
);

SET @site_id := (SELECT id FROM sites WHERE slug = 'demo' LIMIT 1);

-- Autor
INSERT IGNORE INTO authors (site_id, name, slug, bio, expertise) VALUES
(@site_id, 'Equipo Editorial', 'equipo-editorial',
 'Reseñamos y analizamos productos con criterio técnico real y pruebas en escenarios de uso concreto.',
 'Ciberseguridad, SaaS B2B, privacidad');
SET @author_id := (SELECT id FROM authors WHERE site_id = @site_id AND slug = 'equipo-editorial' LIMIT 1);

-- Categorias
INSERT IGNORE INTO categories (site_id, slug, name, description, sort_order) VALUES
(@site_id, 'antivirus-empresas', 'Antivirus para empresas',
 'Endpoint protection y EDR para equipos de 5 a 500 usuarios.', 1),
(@site_id, 'gestion-contrasenas', 'Gestión de contraseñas',
 'Password managers con SSO, compartidos y auditoria.', 2),
(@site_id, 'vpn-empresas', 'VPN para empresas',
 'Acceso remoto seguro, ZTNA y segmentacion.', 3);

SET @cat_av := (SELECT id FROM categories WHERE site_id = @site_id AND slug = 'antivirus-empresas' LIMIT 1);
SET @cat_pw := (SELECT id FROM categories WHERE site_id = @site_id AND slug = 'gestion-contrasenas' LIMIT 1);
SET @cat_vpn := (SELECT id FROM categories WHERE site_id = @site_id AND slug = 'vpn-empresas' LIMIT 1);

-- Affiliate links (sin URL real; placeholders https://example.com)
INSERT IGNORE INTO affiliate_links (site_id, name, destination_url, tracking_slug, network_name, active) VALUES
(@site_id, 'Bitdefender GravityZone',   'https://example.com/bitdefender',   'bitdefender', 'Impact',      1),
(@site_id, '1Password Business',        'https://example.com/1password',     '1password',   'Impact',      1),
(@site_id, 'NordLayer',                 'https://example.com/nordlayer',     'nordlayer',   'PartnerStack',1);

SET @al_bd := (SELECT id FROM affiliate_links WHERE site_id = @site_id AND tracking_slug = 'bitdefender' LIMIT 1);
SET @al_1p := (SELECT id FROM affiliate_links WHERE site_id = @site_id AND tracking_slug = '1password'   LIMIT 1);
SET @al_nl := (SELECT id FROM affiliate_links WHERE site_id = @site_id AND tracking_slug = 'nordlayer'   LIMIT 1);

-- Productos
INSERT IGNORE INTO products (
    site_id, category_id, affiliate_link_id, slug, name, brand,
    description_short, description_long, rating, price_from, price_currency, pricing_model,
    features, pros, cons, specs, featured
) VALUES
(@site_id, @cat_av, @al_bd, 'bitdefender-gravityzone', 'Bitdefender GravityZone Business', 'Bitdefender',
 'Endpoint protection con bajo impacto en performance y consola centralizada.',
 'GravityZone ofrece protección multicapa: antimalware, EDR y control de dispositivos.\n\nIdeal para PyMEs que necesitan consola unificada sin complejidad de gestión.',
 4.6, 49.99, 'USD', 'yearly',
 JSON_ARRAY('Antimalware en tiempo real','EDR con búsqueda de amenazas','Control de dispositivos','Consola en la nube'),
 JSON_ARRAY('Detección muy alta en pruebas independientes','Bajo consumo de recursos','Deploy sencillo'),
 JSON_ARRAY('UI de la consola con curva de aprendizaje','Reportes avanzados en tiers superiores'),
 JSON_OBJECT('Plataformas','Windows, macOS, Linux, iOS, Android','Consola','Cloud','Licencia','Por endpoint/año'),
 1),

(@site_id, @cat_pw, @al_1p, '1password-business', '1Password Business', '1Password',
 'Password manager empresarial con SSO, aprovisionamiento SCIM y auditoria robusta.',
 '1Password Business es el estandar en gestion de credenciales: integraciones con Okta, Azure AD, y un modelo de seguridad basado en Secret Key que evita ataques de cracking masivo.',
 4.8, 7.99, 'USD', 'monthly',
 JSON_ARRAY('SSO (Okta, Azure AD, Google)','Aprovisionamiento SCIM','Bovedas compartidas','Informes de actividad','Secrets Automation'),
 JSON_ARRAY('UI impecable','Modelo de seguridad robusto (Secret Key)','Excelente soporte'),
 JSON_ARRAY('Precio superior al promedio','Reportes basicos en plan base'),
 JSON_OBJECT('Planes','Business, Enterprise','SSO','Si','Apps','macOS, Windows, Linux, iOS, Android, Web'),
 1),

(@site_id, @cat_vpn, @al_nl, 'nordlayer', 'NordLayer (Teams VPN)', 'Nord Security',
 'VPN empresarial con gateways dedicados, ZTNA y facil onboarding.',
 'NordLayer permite dar acceso remoto seguro con gateways dedicados por empresa, listas de control de acceso y politicas basadas en identidad.',
 4.4, 8.00, 'USD', 'monthly',
 JSON_ARRAY('Gateways dedicados','ZTNA con reglas por identidad','Integracion con Azure AD/Okta','IP fija por empresa'),
 JSON_ARRAY('Onboarding rapido','Apps nativas solidas','Buena relacion precio/features'),
 JSON_ARRAY('Menos maduro que soluciones enterprise como Zscaler','Reportes aun en evolucion'),
 JSON_OBJECT('Tipo','VPN + ZTNA','Planes','Basic, Advanced, Premium','Apps','Win, macOS, Linux, iOS, Android'),
 1);

SET @p_bd := (SELECT id FROM products WHERE site_id = @site_id AND slug = 'bitdefender-gravityzone' LIMIT 1);
SET @p_1p := (SELECT id FROM products WHERE site_id = @site_id AND slug = '1password-business'      LIMIT 1);
SET @p_nl := (SELECT id FROM products WHERE site_id = @site_id AND slug = 'nordlayer'               LIMIT 1);

-- Articulos
INSERT IGNORE INTO articles (
    site_id, category_id, author_id, slug, title, subtitle, excerpt, content,
    article_type, related_product_ids, meta_title, meta_description,
    status, published_at
) VALUES
(@site_id, @cat_av, @author_id,
 'guia-ciberseguridad-pymes-2026',
 'Guía completa de ciberseguridad para PyMEs en 2026',
 'Prioridades realistas, presupuesto razonable y herramientas probadas.',
 'Cómo construir una postura de seguridad sólida sin romper el presupuesto.',
 '## Por qué las PyMEs son objetivo\n\nNo es que seas famoso: los atacantes **automatizan** el reconocimiento y cualquier empresa con un RDP expuesto es un objetivo viable.\n\n## Stack mínimo recomendado\n\n- **Endpoint protection moderno (EDR)**: no alcanza con antivirus tradicional.\n- **Gestor de contraseñas** empresarial con SSO.\n- **MFA obligatorio** en todos los servicios críticos.\n- **Backup offline** con rotación semanal.\n\n## Orden de implementación\n\n1. MFA en email y accesos administrativos.\n2. Password manager con onboarding de todo el equipo.\n3. EDR en todos los endpoints.\n4. Backup con pruebas de restauración.\n\n> La mayoría de los incidentes que vemos en PyMEs se habrían prevenido con estos cuatro puntos.\n\n## Errores comunes\n\n- Comprar una herramienta sin onboardear al equipo.\n- Dejar MFA como "opcional".\n- Confiar solo en el antivirus que viene con Windows.',
 'guide',
 JSON_ARRAY(@p_bd, @p_1p),
 'Guía de ciberseguridad para PyMEs 2026',
 'Prioridades, stack mínimo y orden de implementación para proteger una PyME sin gastar de más.',
 'published', NOW() - INTERVAL 5 DAY),

(@site_id, @cat_av, @author_id,
 'bitdefender-vs-kaspersky-eset',
 'Bitdefender vs Kaspersky vs ESET para empresas',
 'Comparativa práctica según resultados de AV-Test, costos y experiencia real.',
 'Quién gana en detección, quién en consola, y quién conviene según el tamaño de empresa.',
 '## Detección\n\nLos tres rankean alto en AV-Test y SE Labs. Diferencias marginales en deteccion pura; lo que importa son los **falsos positivos** y la consola de administración.\n\n## Consola y gestión\n\n- **Bitdefender**: cloud-first, rápida, alertas claras.\n- **Kaspersky**: muy potente pero pesada; mejor para equipos con SysAdmin dedicado.\n- **ESET**: consola simple y ligera, ideal para PyMEs sin IT interno.\n\n## Precio por endpoint (referencia)\n\n| Solución      | Precio/endpoint/año |\n|---------------|---------------------|\n| Bitdefender   | 50 USD              |\n| Kaspersky     | 45 USD              |\n| ESET          | 40 USD              |\n\n## Recomendación\n\n- <30 endpoints sin IT interno: **ESET**.\n- 30-200 endpoints con necesidad de EDR real: **Bitdefender**.\n- Entornos regulados complejos con SysAdmin senior: **Kaspersky** (evaluando temas geopolíticos).',
 'comparison',
 JSON_ARRAY(@p_bd),
 'Bitdefender vs Kaspersky vs ESET: comparativa 2026',
 'Detección, consola y precio: qué antivirus para empresas elegir según tu tamaño y necesidad.',
 'published', NOW() - INTERVAL 3 DAY),

(@site_id, @cat_pw, @author_id,
 'resena-1password-business',
 '1Password Business: reseña después de 12 meses de uso',
 'Lo que funciona, lo que extrañamos y cuándo no tiene sentido pagarlo.',
 'Usamos 1Password Business a diario por un año. Pros reales, contras honestos y alternativas.',
 '## Setup\n\nEl deploy con Okta SCIM tomó menos de una hora. La UX de onboarding para usuarios no técnicos es de lo mejor que vimos.\n\n## Uso diario\n\n- Las **bóvedas compartidas** por equipo son claras y evitan el problema clasico de compartir creds por chat.\n- El **autocompletado** es rápido y no rompe formularios raros.\n- La **integración con CLI** ayuda para dev/ops (secrets en shell scripts).\n\n## Lo que extrañamos\n\n- Reportes mas profundos sin ir a planes Enterprise.\n- Auditoria granular por campo.\n\n## ¿Vale la pena?\n\n**Sí**, si tu equipo supera las 10 personas y ya estás en un IdP (Okta/Azure AD). Para equipos mas chicos, **Bitwarden** da 80% del valor por 20% del precio.',
 'review',
 JSON_ARRAY(@p_1p),
 '1Password Business: reseña real después de 12 meses',
 'Experiencia completa con 1Password Business: deploy, uso diario, pros y cuando no conviene.',
 'published', NOW() - INTERVAL 1 DAY);

INSERT INTO migrations (filename) VALUES ('002_demo_seed.sql');
