<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Password Reminder Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines are the default lines which match reasons
	| that are given by the password broker for a password update attempt
	| has failed, such as for an invalid token or invalid new password.
	|
	*/

    // Common
    'yes' => 'Sí',
    'no' => 'No',
    'close' => 'Cerrar',

    "sending"=>"Enviando...",
    "finalize"=>"Finalizar",

    //Dashboard
    "mostvisited_items" => "Los últimos más visitados",
    "recent_items" => "Los más recientes",
    "last_visited_items" => "Vistos recientemente",

    //Category, Search
	"categories_title" => "Categorías",
    "sub_categories_title" => "Sub-categorías",
    "product_title" => "Productos",
    "services_title"   => "Servicios",
    "sell_by"          => "Por",
    "sell_by_full"          => "Publicado por",
    "see_product_detail" => "Ver detalle",
    "phone" => "Teléfono",
    "contacts" => "Contactos, Sucursales, Distribuidores",
    "city" => "Ciudad",
    "state" => "Estado",
    "states" => "Estados",
    "location" => "Ubicación",
    "contacts_more_info" => "<b>Para obtener mayor información de contacto (dirección, teléfono y correo) sobre el anunciante, contactos, sucursales o distribuidor te invitamos a </b><a href=':loginUrl' class='btn btn-primary btn'>Ingresar</a><b> o </b><a nohref onclick='Mercatino.registerForm.show()' class='btn btn-primary btn'>Registrarte</a>",

    //Search
    "publications_search_placeholder" => "Buscar...",
    "search_results" => "Resultados para",
    "search_no_results" => "No se encontraron :item para: :criteria",
    "view" => "Ver",
    "search" => "Buscar",

    //Publications
    "publication" => "Publicacion|Publicaciones",
    "rate_publication" => "Evaluar publicación",
    "rate_it" => "Calificar",
    "report_publication_msg"  => "Denunciar a este usuario por publicar contenido no apropiado",
    "report_instructions" => "Razones por las cuales deseas denunciar esta publicación:",
    "report_commend_required" => "Debe indicar las razones de la denuncia",
    "rating_comment_required" => "Por favor indique los comentarios",
    "rating_title_required" => "Por favor indique el título",
    "report_it" => "Denunciar",
    "id" => "ID",
    "title" => "Título",
    "short_description" => "Descripción corta",
    "long_description" => "Descripción extendida",
    "descripcion" => "Descripción",
    "from_date" => "Desde",
    "to_date" => "Hasta",
    "visits_number" => "Visitas",
    "created_at" => "Creado",
    "categories_name" => "Categorías",
    "publication_categories" => "Categorías para esta publicación",
    "publication_contacts" => "Contactos para esta publicación",
    "publication_images" => "Imágenes para esta publicación",
    "publication_images_advice" => "Para garantizar la calidad de las imágenes, asegúrate de subir imágenes preferiblemente <b>cuadradas</b> de <b>mínimo :min_width x  :min_height px</b> y que no excedan <b>2MB</b>. El sistema reconoce <b>formatos gif, jpeg y png</b>.",
    "advertising_images_advice" => "Para garantizar la calidad de slas imágenes, asegúrate de subir imágenes de <b>mínimo :min_width x :min_height px</b> (o mayores con esas proporciones) y que no excedan <b>2MB</b>. El sistema reconoce <b>formatos gif, jpeg y png</b>.",
    "options" => "Opciones",
    'status' => 'Estatus',
    "seller_name" => 'Anunciante',
    "rating_avg" => 'Puntaje',
    "evaluation" => 'Evaluación',
    "remember_publication" => 'Recordar vencimiento de la publicación vía correo',
    "latitude" => 'Latitud',
    "longitude" => 'Longitud',
    "ratings" => "Calificaciones",
    "no_rating_avg" => "No ha sido puntuado.",

    //Publication status
    'status_publication_Draft' => 'Borrador',
    'status_publication_Published' => 'Publicada',
    'status_publication_On_Hold' => 'Pausada',
    'status_publication_Suspended' => 'Suspendida',
    'status_publication_Finished' => 'Finalizada',

    // Reports
    'reports' =>  'Denuncias',
    'report_title' => 'Denuncia',
    'backend_report_view_title' => 'Denuncia pendiente',
    'backend_report_by_user' => 'Denuncias hechas por el usuario :u',
    'backend_report_by_publication' => 'Denuncias hechas a la publicación :p',
    'valid_report' => 'Denuncia válida',
    'invalid_report' => 'Denuncia inválida',
    'suspend_publication' => 'Editar/Suspender publicación',
    'suspend_user' => 'Editar/Suspender usuario',

    // Reports status
    'status_report_Pending' => 'Pendiente',
    'status_report_Correct' => 'Correcto',
    'status_report_Incorrect' => 'Incorrecto',

    // Reports search
    'reports_search_placeholder' => 'Buscar...',

    // Reports messages
    'report_message_invalid_data' => 'Datos inv&aacute;lidos.',
    'report_message_not_exist' => 'La denuncia a la que hace referencia no existe.',
    'report_message_change_success' => 'La denuncia ha sigo actualizada exitosamente',

    //Ratings
    'rate_instructions' => 'Califica la publicación del 1 al 5, donde 1 es la puntuación más baja y 5 la más alta.',
    'rate_title' => 'Título',
    'rate_comment_instructions' => "Comentarios sobre la calificación (max. 300 caracteres):",

    //Menu
    "home" => "Inicio",
    "products_and_services" => "Productos y Servicios",
    "about_us" => "Acerca de",
    "partners" => "Aliados",
    "contact" => "Contacto",
    "help" => "Ayuda",
    "jobs" => "Bolsa de Trabajo",
    "edit_job" => "Editar oferta de trabajo",
    "my_profile" => "Mi Perfil",
    "my_publications" => "Mis publicaciones",
    "my_jobs" => "Mis ofertas laborales",
    "admin_dashboard" => "Administración",
    "postulation" => "Quiero publicar",
    "logout" => "Cerrar sesión",
    "forgot_password" => "¿Olvidaste tu contraseña?",

    "password"=>"Contraseña",
    "password_confirmation"=>"Confirme la contraseña",
    "restore_password" => "Restaurar contraseña",

    //Footer
    "address_line1" => "Av. San Juan Bosco, Edif. Centro Altamira,",
    "address_line2" => "Nivel Mezzanina, 1060, Caracas, Venezuela",
    "phones_label" => "Teléfonos:",
    "phones_line1" => "58-212-2632427 / 2634614",
    "phones_line2" => "58-212-2642845 / 2643742",
    "fax_label" => "Fax:",
    "fax_line1" => "58-212-2647213",
    "tumercato_email" => "info@tumercato.com",
    "facebook" => "CAVENIT",
    "twitter" => "@cavenit",
    "copyright" => "&copy; Copyright 2013 CAVENIT (RIF J-00066510-9) - TuMercato.com &nbsp; | &nbsp; Desarrollado por",
    "androb" => "ANDROB",

    //Options
    "new_publication" => "Nueva publicación",
    "edit_publication" => "Editar publicación",
    "edit_images" => "Imágenes",
    "see" => "Ver",
    "edit" => "Editar",
    "delete" => "Eliminar",
    "cancel" => "Cancelar",
    "save" => "Guardar",
    "send" => "Enviar",
    "continue"  => "Continuar",
    "see_publication" => 'Ver publicación',
    "see_my_publications" => "Ir a mis publicaciones",
    "remove_image" => "Eliminar imagen",
    "add_images_msg" => "Arrastra las imágenes o haz click para cargarlas",
    "exit" => "Salir",

    // Backend Menu
    "backend_menu_title" => "SECCIONES DE ADMINISTRACIÓN",
    "backend_menu_dashboard" => "Dashboard",
    "backend_menu_users" => "Usuarios",
    "backend_menu_publishers" => "Anunciantes",
    "backend_menu_publications" => "Publicaciones",
    "backend_menu_advertisings" => "Publicidades",
    "backend_menu_stats" => "Estadísticas",
    "backend_menu_reports" => "Denuncias",
    "backend_search_publication_title" => "Buscar publicaciones",
    "backend_search_user_title" => "Buscar usuarios",
    "backend_search_advertiser_title" => "Buscar anunciantes",

    // Backend Dashboard
    "backend_users_section_title" => "Aprobaciones pendientes",
    "backend_reports_section_title" => "Denuncias pendientes",
    "backend_user_approve" => "Aprobar",
    "backend_user_not_approve" => "Desaprobar",

    // Backend reports total
    "backend_reports_total" => "Denuncias",
    'backend_report_total_view_title' => 'Detalle de denuncia',

    // Messages
    "delete_publication_invalid" => "La publicación que intentas eliminar es inválida",
    "delete_publication_success" => "La publicación se ha eliminado correctamente",
    "delete_publication_error" => "Ha ocurrido un error al intentar eliminar la publicación.",
    "modal_publication_delete_title" => "Eliminar publicación",
    "modal_publication_delete_content" => "¿Está seguro que deseas eliminar esta publicación?",
    "report_send_success" => "La denuncia se ha enviado correctamente",
    "report_send_error" => "Ha ocurrido un error enviando la denuncia",
    "rating_send_success" => "La calificación se ha enviado correctamente",

    // Advertising
    "name" => "Nombre publicidad",
    "status" => 'Estatus',
    "image_url" => "Imagen",
    "external_url" => "Url externa",
    "full_name" => "Nombre completo de persona contacto",
    "order" => "Orden",
    "email" => "Correo de contacto",
    "phone1" => "Teléfono 1 de contacto",
    "phone2" => "Teléfono 2 de contacto",
    "advertising_images" => "Imagen para esta publicidad",
    "advertisings" => "Publicidades",

    //Advertising status
    'status_Draft' => 'Borrador',
    'status_Published' => 'Publicada',
    'status_Trashed' => 'Eliminada',

    // Options
    "new_advertising" => "Nueva publicidad",
    "edit_advertising" => "Editar publicidad",
    "continue" => "Continuar",
    "products" => "Productos",
    "services" => "Servicios",

    // Messages
    "edit_advertising_success" => "La publicidad se ha guardado correctamente",
    "edit_advertising_error" => "Ha ocurrido un error al intentar guardar la publicidad",
    "delete_advertising_invalid" => "La publicidad que intenta eliminar es inválida",
    "delete_advertising_success" => "La publicidad se ha eliminado correctamente",
    "delete_advertising_error" => "Ha ocurrido un error al intentar eliminar la publicidad",
    "modal_advertising_delete_title" => "Eliminar publicidad",
    "modal_advertising_delete_content" => "¿Estás seguro que deseas eliminar esta publicidad?",
    "no_elements_to_list" => "No hay elementos para mostrar",

    // Search
    "advertisings_search_placeholder" => "Buscar...",

    //Date format
    "date_format" => 'dd-mm-aaaa',
    "date_format_php" => 'd-m-Y',

    "login_email" => "Correo electrónico",
    "login_password" => "Contraseña",
    "login_remember" => "Recuérdame",
    "login_signin" => "Ingresar",
    "login_error" => "Verifica tu usuario y contraseña ",
    "login_header" => "Ingresa si estás registrado",
    "starting_session" => "Iniciando sesión en TuMercato...",
    "register_email" => "Correo electrónico",
    "register_full_name" => "Nombre persona / Razón social",
    "register_phone1" => "Teléfono 1 (ej. 0XXX-YYYYYYY)",
    "register_phone2" => "Teléfono 2 (ej. 0XXX-YYYYYYY)",
    "register_password" => "Contraseña",
    "register_password_confirmation" => "Confirmar contraseña",
    "register_signup" => "Regístrate",
    "creating_account" => "Procesando...",
    "register_header" => "Regístrate si eres un nuevo usuario o empresa",
    "register_error" => "Debes llenar todos los campos",
    "register_finalize" => "Omitir",
    "register_conditions" => "Acepto <a class='manito' nohref onclick='javascript:showConditions();'>términos y condiciones de servicio</a>",
    "register_dialog_description" => "",
    "register_dialog_continue" => "Sí quiero",
    "register_dialog_cancel" => "No por los momentos, quiero navegar",
    "register_title_success" => "Bienvenido",
    "register_description_success" => "Proceso de registro completo",
    "register_hide_conditions_back" => "<< Regresar y completar el registro",

    "register_dialog_activated" => "Tu usuario ha sido activado",
    "register_dialog_header" => "¿Quieres optar a ser anunciante?",
    "register_dialog_description" => "Para ser anunciante, tienes que ser un asociado a la Cámara de Comercio Venezolano-Italiana (CAVENIT) y pasarás por un proceso de aprobación luego de responder una serie de preguntas. Si cumples estos requisitos y deseas optar a publicar, selecciona 'Sí quiero'.",
    "register_dialog_description2" => "De lo contrario, si no perteneces a la Cámara de Comercio Venezolano-Italiana (CAVENIT) o no deseas optar a publicar por los momentos, selecciona 'No por los momentos, quiero navegar' para proceder a ingresar.",
    "publisher_header" => "Datos para anunciantes",
    "publisher_explanation" => "Llena los siguientes campos si eres un asociado a la Cámara de Comercio Venezolano-Italiana (CAVENIT) y deseas publicar productos y/o servicios en Mercatino. De lo contrario omite este paso, ya que toda postulación para publicar será sometida a un proceso de revisión y se informará oportunamente a quienes sean aprobados.",
    "select_person_type" => "Selecciona Persona Natural o Jurídica",
    "select_id_type" => "Tipo de documento",
    "publisher_id" => "Cédula / Rif",
    "publisher_type" => "Tipo de Persona",
    "publisher_type_person" => "Persona Natural",
    "publisher_type_business" => "Persona Jurídica",
    "publisher_city" => "Dirección",
    "publisher_city" => "Ciudad",
    "publisher_state" => "Estado",
    "publisher_phone1" => "Teléfono 1",
    "publisher_phone2" => "Teléfono 2",
    "publisher_media" => "¿Cómo te enteraste de nosotros?",
    "select_state" => "Seleccione Estado",
    "publisher_seller" => "Nombre vendedor / Nombre empresa",
    "publisher_create" => "Siguiente",
    "publisher_error" => "Ingresa todos los datos por favor",
    "publisher_categories" => "Selecciona todos los sectores de negocio que apliquen",
    "publisher_finalize" => "Terminar",
    "publisher_signup" => "Continuar",

    "contacts_header" => "Agregar contactos, distribuidores o sucursales",
    "contact_add" => "Agregar",
    "contact_full_name" => "Persona de contacto",
    "contact_distributor" => "Nombre de la distribuidora o sucursal",
    "contact_phone" => "Teléfono",
    "contact_phone1" => "Teléfono 1",
    "contact_phone2" => "Teléfono 2",
    "contact_phones" => "Teléfonos",
    "contact_address" => "Dirección",
    "contact_state" => "Estado",
    "contact_city" => "Ciudad",
    "contact_email" => "Correo electrónico",
    "contact_not_found" => "No has agregado contactos",
    "contact_add_contact" => "Agregar contacto",

    "contact_delete_success" => "El contacto se ha eliminado correctamente",
    "contact_delete_error" => "Ha ocurrido un error al intentar eliminar el contacto",

    "publisher_success_title" => "¡Su solicitud ha sido enviada de manera exitosa!",
    "publisher_success_message" => "Recibirá notificación de su aprobación en menos de 24 horas.",
    "publisher_success_accept" => "Aceptar",

    "backend_email" => "Correo electrónico",
    "backend_full_name" => "Nombre persona / Razón social",
    "backend_seller" => "Nombre vendedor / Nombre empresa",
    "backend_id" => "Cédula / Rif",
    "backend_phone" => "Teléfono",

    "backend_report_id" => "ID",
    "backend_report_user" => "Nombre",
    "backend_report_publication" => "Publicación",
    "backend_report_comment" => "Comentario",
    "backend_report_date" => "Fecha",
    "backend_report_status" => "Estatus",

    "backend_report_status_Pending" => "Pendiente",
    "backend_report_status_Correct" => "Correcto",
    "backend_report_status_Incorrect" => "Incorrecto",

    //Flash messages
    "add_publication_success" => 'La publicación se ha creado correctamente, aquí podrás <strong>agregar las imágenes</strong> de tu nueva publicación',
    "edit_publication_success" => 'La publicación se ha guardado correctamente',
    "add_publication_image_success" => 'La imagen se ha cargado correctamente',
    "add_publication_image_error" => 'Ha ocurrido un error cargando la imagen',
    "add_publication_image_error_size" => 'La imagen a cargar debe tener un tamaño mínimo de :min_widthx:min_height pixeles',
    "add_advertising_image_error_size" => 'La imagen a cargar debe tener un tamaño mínimo de :min_widthx:min_height pixeles',
    "delete_publication_image_success" => 'La imagen se ha eliminado correctamente',
    "delete_publication_image_error" => 'Ha ocurrido un error eliminando la imagen',
    "edit_publication_change_key_field" => 'Si se modifica este campo el contador de visitas de la publicación será reiniciado.',
    'edit_publication_redo_key_field' => 'Este cambio implica reiniciar el contador de la publicación. Para deshacer haz click :a_open aquí. :a_close',

    "select" => "Selecciona",

    "site_description"=> "Mercatino es el punto de encuentro para promocionar el intercambio comercial en la comunidad venezolano-italiana en la web y dar a conocer la fuerza económica que representa. Aquí los usuarios inscritos podrán visualizar los productos y servicios publicados por otros usuarios y empresas asociadas a la Cámara de Comercio Venezolano-Italiana (CAVENIT), con  el motivo final de reactivar la economía en la comunidad y hacer negocios con más confianza y seguridad.",
    "site_welcome" => "Bienvenido a TuMercato",
    "site_messages_title_error" => "Importante",

    //Help
    "help_publication_images_title" => "Imágenes de mi publicación",
    "help_publication_images" => "La publicación se ha creado y ahora puedes agregar las imágenes de tu nueva publicación. <br/><br/>Para agregar una imagen, simplemente arrástrala a la caja señalada o haz clic en dicha caja.",
    "help_publication_dates" => "Las publicaciones permanecerán vigentes por un máximo de 3 meses. Puedes renovar tu publicación todas las veces que desees",
    "help_publication_map" => "Puedes especificar latitud y longitud de tu ubicación principal si deseas que aparezca en un mapa en el detalle de la publicación",
    "help_publication_categories" => "Puedes elegir sub-categorías al expandir la categoría principal",

    "auth_menu_my_profile" => "Editar perfil",

    "profile" => "Perfil de usuario",
    "profile_edit" => "Edita tu perfil",
    "profile_edit_basic" => "Datos Básicos",
    "profile_edit_publisher" => "Datos de Publicador",
    "profile_edit_sectors" => "Sectores de Negocio",
    "profile_edit_contacts" => "Contactos",
    "profile_email" => "Correo electrónico",
    "profile_full_name" => "Nombre persona / Razón social",
    "profile_change_password" => "Cambia tu contraseña",
    "profile_current_password" => "Contraseña actual",
    "profile_password" => "Contraseña",
    "profile_password_confirmation" => "Confirma Contraseña",
    "profile_seller_name" => "Nombre vendedor / Nombre empresa",
    "profile_publisher_type" => "Tipo de Persona",
    "profile_id" => "Cédula / Rif",
    "profile_state" => "Estado",
    "profile_city" => "Ciudad",
    "profile_address" => "Dirección",
    "profile_phone1" => "Teléfono 1",
    "profile_phone2" => "Teléfono 2",
    "profile_avatar" => "Imagen de perfil",
    "profile_view_contact" => "Detalle de contacto",
    "profile_edit_contact" => "Editar contacto",
    "profile_delete_contact_title" => "Eliminar contacto",
    "profile_delete_contact_content" => "¿Está seguro que desea eliminar este contacto?",

    // File Uploader
    "fileuploader_select_image" => "Seleccione imagen",
    "fileuploader_change" => "Cambiar",
    "fileuploader_remove" => "Eliminar",

    // Messages
    "profile_update_success" => "Su perfil ha sido actualizado exitosamente",
    "profile_update_file_error" => "Ha ocurrido un error al intentar guardar la imagen de perfil",
    "profile_update_file_delete_error" => "Ha ocurrido un error al intentar reemplazar la imagen de perfil",
    "profile_edit_contact_error" => "Ha ocurrido un error al intentar guardar el contacto",
    "profile_edit_contact_success" => "El contacto ha sido editado exitosamente",
    "profile_add_contact_success" => "El contacto ha sido agregado exitosamente",

    //filters
    "filter_active" => 'Filtros Activos',
    "filter_available" => 'Filtros Disponibles',
    "filter_seller_title" => 'Anunciante',
    "filter_state_title" => 'Ubicación',
    "filter_category_title" => 'Categoría',
    "filter_publication_start_date" => 'Fecha de inicio:',
    "filter_publication_end_date" => 'Fecha fin:',
    "filter_publication_publisher"=> 'Anunciante:',
    "filter_publication_category" => 'Categoría:',
    "filter_publication_status" => 'Estatus:',
    "filter_user_role" => "Rol:",
    "filter_status_placeholder" => 'Seleccione Estatus',
    "filter_category_placeholder" => 'Seleccione una o más categorías',
    "filter_publisher_placeholder" => 'Seleccione uno o más anunciantes',
    "select_default"=>"Seleccione",

    "filter_role_placeholder" => 'Seleccione Rol',
    // Users
    "users" => 'Usuarios',
    "user_name" => 'Nombre',
    "user_email" => 'Correo',
    "user_role" => 'Rol',
    "user_status" => 'Estatus',
    "user_is_publisher" => '¿Es anunciante?',

    // Labels
    "new_user" => 'Nuevo usuario',
    "new_user_admin" => 'Nuevo administrador',
    "edit_user" => 'Editar usuario',
    "reset_search" => 'Limpiar busqueda',
    "advanced_search" => 'Opciones de busqueda',

    // Search
    "user_search_placeholder" => "Buscar...",
    "user_role_Admin" => "Administrador",
    "user_role_Publisher" => "Publicador",
    "user_role_Basic" => "Básico",

    // Status
    'status_Active' => 'Activo',
    'status_Inactive' => 'Inactivo',
    'status_Suspended' => 'Suspendido',

    // Roles
    'role_Basic' => 'Básico',
    'role_Publisher' => 'Publicador',
    'role_Admin' => 'Administrador',

    // Messages
    'save_user_success' => 'El usuario ha sido guardado satisfactoriamente',
    "modal_user_delete_title" => "Eliminar usuario",
    "modal_user_delete_content" => "¿Está seguro que deseas eliminar este usuario?",
    "delete_user_invalid" => "El usuario que intenta eliminar es inválido",
    "delete_user_success" => "El usuario se ha eliminado correctamente",
    "delete_user_error" => "Ha ocurrido un error al intentar eliminar el usuario.",
    "rating_publication_error" => "Ha ocurrido un error enviando la calificación, intente nuevamente.",
    "rating_publication_empty_error" => "Debe indicar una puntuación o un comentario para que la calificación sea válida.",
    "rating_publication_no_items" => "Esta publicación aún no tiene calificaciones.",
    "rating_publication_retrieve_error" => "Ha ocurrido un error intentando recuperar calificaciones para la publicación.",
    "rating_status_admin_label" => "Estatus de la calificación:",
    "rating_owner_label" => "Eliminar calificación:",
    "rating_status_on_admin_label" => "Activa",
    "rating_status_off_admin_label" => "Inactiva",
    "rating_owner_delete_label" => "Eliminar",
    "rating_change_status_on" => "La califición ha sido activada satisfactoriamente",
    "rating_change_status_off" => "La califición ha sido desactivada satisfactoriamente",
    "rating_change_status_error" => "Ha ocurrido un error al intentar cambiar el estatus de esta calificación",
    "rating_delete_success" => "La calificación ha sido eliminada satisfactoriamente",
    "rating_delete_error" => "Ha ocurrido un error al intentar eliminar esta calificación",
    "rating_get_more" => "Ver más",

    // Advertiser
    "advertisers" => 'Anunciantes',
    "advertiser_name" => 'Nombre',
    "advertiser_email" => 'Correo',
    "advertiser_status" => 'Estatus',

    // Labels
    "new_advertiser" => 'Nuevo anunciante',
    "edit_advertiser" => 'Editar anunciante',
    "reset_search" => 'Nueva búsqueda',

    // Search
    "advertiser_search_placeholder" => "Buscar...",

    // Messages
    'save_advertiser_success' => 'El anunciante ha sido guardado satisfactoriamente',
    "delete_advertiser_invalid" => "El anunciante que intentas eliminar es inválido",
    "delete_advertiser_success" => "El anunciante se ha eliminado correctamente",
    "delete_advertiser_error" => "Ha ocurrido un error al intentar eliminar el anunciante.",
    "modal_advertiser_delete_title" => "Eliminar anunciante",
    "modal_advertiser_delete_content" => "¿Está seguro que deseas eliminar este anunciante?",

    // General
    "required_label" => "Campo Obligatorio",
    "phone_format_label" => "Formato: 0XXX-YYYYYYY",
    "external_url_label" => "Debe comenzar con http://",
    "order_label" => "Valor numérico más alto tiene mayor prioridad",

    // Contactanos form
    "contactus" => "Contacto",
    "contactus_name" => "Nombre completo",
    "contactus_email" => "Correo",
    "contactus_phone" => "Teléfono",
    "contactus_subject" => "Asunto",
    "contactus_message" => "Mensaje",
    "contactus_success" => "Su mensaje ha sido enviado satisfactoriamente",
    "contactus_email_new_message_subject" => "Nuevo mensaje desde el formulario de contacto",
    "contactus_cavenit" => "Sedes de CAVENIT",
    "contactus_caracas" => "Caracas",
    "caracas_email" => "informatica@cavenit.com",
    "caracas_address_line1" => "Av. San Juan Bosco, Edif. Centro Altamira,",
    "caracas_address_line2" => "Nivel Mezzanina, 1060, Caracas",
    "contactus_acarigua" => "Acarigua",
    "acarigua_address_line1" => "Av. 13 de Junio, Quinta Azahar, 3302,",
    "acarigua_address_line2" => "Acarigua, Edo. Portuguesa",
    "acarigua_phones_line1" => "58-255-6221462 / 6642750",
    "acarigua_phones_line2" => "58-255-6215872 / 6239086",
    "acarigua_email" => "acarigua@cavenit.com",
    "contactus_maracay" => "Maracay",
    "maracay_address_line1" => "Casa de Italia de Maracay, Calle Los Nísperos,",
    "maracay_address_line2" => "La Floresta, Piso 2, 2102, Maracay, Edo. Aragua",
    "maracay_phones_line1" => "58-243-2427741",
    "maracay_email" => "maracay@cavenit.com",
    "contactus_valencia" => "Valencia",
    "valencia_address_line1" => "Urbanización La Trigaleña Av. 91 (Italia),",
    "valencia_address_line2" => "Nº 132-371, 2001, Valencia, Edo. Carabobo",
    "valencia_phones_line1" => "58-241-8432757 / 8432757",
    "valencia_email" => "valencia@cavenit.com",
    "contactus_maracaibo" => "Maracaibo",
    "maracaibo_address_line1" => "C.C. Las Tejas, Calle 68 con con Av.20 PB",
    "maracaibo_address_line2" => "1-1, El Paraíso, 4001, Maracaibo, Edo. Zulia",
    "maracaibo_phones_line1" => "58-261-7834044",
    "maracaibo_email" => "maracaibo@cavenit.com",
    "contactus_barquisimeto" => "Barquisimeto",
    "barquisimeto_address_line1" => "Club Italo Carretera Vía El Ujano,",
    "barquisimeto_address_line2" => "3001, Barquisimeto, Edo. Lara",
    "barquisimeto_phones_line1" => "58-251-9767416",
    "barquisimeto_email" => "barquisimeto@cavenit.com",
    "contactus_ptoordaz" => "Puerto Ordaz",
    "ptoordaz_address_line1" => "Carrera Ciudad Piar, Edif. Uyapar, Ofc. 2-7,",
    "ptoordaz_address_line2" => "Castillito, 8050, Puerto Ordaz, Edo.  Bolívar",
    "ptoordaz_phones_line1" => "58-286-9227705",
    "ptoordaz_email" => "puertordaz@cavenit.com",

    // Acerca de


    // Emails
    'email_restore_user_password' => 'Contraseña Restablecida',

    'email_welcome_user_subject' => '¡Bienvenido a TuMercato.com!',
    'email_new_adviser_request' => 'TuMercato tiene una nueva solicitud para ser anunciante',
    'email_approved_user_notification' => 'Su solicitud de anunciante ha sido aprobada por TuMercato.com',
    'email_disapproved_user_notification' => 'En este momento su solicitud no pudo ser aprobada por TuMercato.com',
    'email_admin_notification_new_report' => 'Se ha generado una nueva denuncia en TuMercato.com',
    'email_publication_next_expire' => 'Su publicación #:pubId en TuMercato.com está por caducar',
    'email_cron_admin_notification_pub_next_expire_subject' => 'Notificación exitosa de próximos vencimientos de publicaciones',
    'email_cron_admin_notification_pub_next_expire_content' => 'Estimado Administrador,<br/><br/>Han sido enviados existosamente los correos de notificación a
                                        los usuarios que tienen publicaciones próximas a vencer.',
    'email_cron_admin_notification_pub_change_status_date_subject' => 'Actualización existosa de publicaciones programadas',
    'email_cron_admin_notification_pub_change_status_date_content' => 'Estimado Administrador,<br/><br/>Han sido publicadas/finalizadas existosamente
                                        las publicaciones de acuerdo al rango de fechas establecido para cada una.',

    // Home - Post activation dialog
    'home_post_activation_title' => 'Usuario registrado',
    'home_post_activation_description' => 'Tu usuario ha sido registrado en TuMercato.com y debes proceder a activarlo
                                    haciendo clic en el enlace que te hemos enviado por correo electrónico para disfrutar
                                    de todas las ventajas adicionales que le ofrece TuMercato.com.',
    'home_post_activation_description2' => 'Si deseas continuar navegando ahora, haz clic en el siguiente botón.',
    'home_post_activation_dialog_go_home' => 'Ir a la página de inicio',

    "stats_users_basic" => "Básico",
    "stats_description_users_basic" => "Cantidad de usuarios Básicos",
    "stats_users_publisher" => "Anunciantes",
    "stats_description_users_publisher" => "Cantidad de usuarios anunciantes que tienen permiso para publicar",
    "stats_users_to_approve" => "Aspirando",
    "stats_description_users_to_approve"=>"Cantidad de usuarios aspirando a ser Anunciantes",
    "stats_publications"=>"Publicaciones",
    "stats_description_publications"=>"Cantidad de Publicaciones",
    "stats_publications_reports"=>"Publicaciones con denuncia",
    "stats_description_publications_reports"=>"Cantidad de publicaciones donde se ha realizado alguna denuncia",
    "stats_reports"=>"Total de denuncias",
    "stats_description_reports" => "Cantidad de denuncias realizadas en todas las publicaciones",
    "stats_products"=>"Productos",
    "stats_description_products"=>"Cantidad de publicaciones por categorías de productos",
    "stats_services"=>"Servicios",
    "stats_description_services"=>"Cantidad de publicaciones por categorías de servicios",
    "stats_publishers"=>"Anunciantes",
    "stats_description_publishers"=>"Cantidad de anunciantes por estado",

    "reminder_question"=>"¿ Cual es tu correo ?",
    "reminder_header"=>"Te ayudaré",
    "reminder_answer"=>"En minutos por favor verifica tu correo, te envíaremos un email con el enlace para cambiar tu contraseña",
    "reminder_success"=>"Verifica tu correo, para que puedas cambiar tu contraseña",
    "reminder_email_subject" => "Recuperación de contraseña para TuMercato.com",

    "reset_token_invalid"=>"El token es invalido, para cambiar la contraseña has click en ¿Olvidaste tu contraseña?",
    "reset_question"=>"Escriba su contraseña nueva",
    "reset_answer"=>"Tu contraseña fue cambiada exitosamente",
    "reset_header"=>"Cambio de contraseña",

    "job_type_contracted"=>"Contratado",
    "job_type_internship"=>"Pasantía",
    "job_type_temporary"=>"Temporal",
    "job_type_independent"=>"Independiente",

    "job_academic_level_secondary" => "Secundaria",
    "job_academic_level_senior_technician" => "Técnico superior",
    "job_academic_level_university" =>"Universitario",
    "job_academic_level_master_specialization" =>"Maestría / Especialización",
    "job_academic_level_phd"=>"Doctorado",

    "male"=>"Masculino",

    "female"=>"Femenino",

    "indistinct"=>"Indistinto",

    "new_job"=> "Nueva oferta",
    "company_name"=>"Nombre de la empresa",
    "job_title"=>"Nombre del cargo",
    "vacancy"=>"Vacantes",
    "job_type"=>"Tipo de cargo",
    "temporary_months"=>"Duración",
    "areas"=>"Áreas",
    "area_sector"=>"Área o sector de la empresa",
    "description"=>"Descripción del cargo",
    "job_location"=>"Ubicación del cargo",
    "requirements"=>"Requisitos",
    "academic_level"=>"Nivel Académico",
    "careers"=>"Carreras afines",
    "experience_years"=>"Experiencia",
    "age"=>"Edad",
    "sex"=>"Sexo",
    "languages"=>"Idiomas",
    "salary"=>"Rango salarial",
    "benefits"=>"Beneficios",
    "start_date"=>"Fecha de publicación",
    "close_date"=>"Fecha de cierre",
    "job_guest"=>"Para ver más información de las ofertas como el nombre, logo y correo de contacto de la empresa, debes <a href=':loginUrl'>ingresar</a> o <a class='manito' nohref onclick='Mercatino.registerForm.show()'>registrarte</a>.",
    "search_job_title"=>"Buscar ofertas",
    "filter_areas"=>"Área/Sector de la empresa",
    "filter_select_areas"=>"Seleccione área o sector",
    "filter_job_date"=>"Fecha de publicación",
    "not_jobs"=>"Todavía no hay ofertas en la bolsa de trabajo. ¡Sé el primero en publicar una!",
    "not_jobs_user"=>"Todavía no hay ofertas en la bolsa de trabajo.",
    "delete_job_invalid"=>"La oferta de trabajo no se pudo eliminar",
    "delete_job_success"=>"Oferta de trabajo eliminada",
    "delete_job_error"=>"La oferta de trabajo no se pudo eliminar",
    "modal_job_delete_title"=>"Advertencia",
    "modal_job_delete_content"=>"¿Realmente deséa eliminar la oferta de trabajo?",
    "contact_email_detail"=>"Envíe su currículo a",
    "previous"=>"Regresar",
    "back_to_publications"=>"Ir a mis publicaciones",
    "more"=>"Mas información",

    "inactive_user" => "Por favor, verifica tu correo y activa tu usuario.",
    "jobs_areas_required" => "El campo áreas es requerido.",
    "year_experience" => " :number año|[2,5] :number años| >:number años",
    "month" => ":number mes|:number meses",
    "year" => " :number año|:number años",
    "web_page" => "Página web"
);