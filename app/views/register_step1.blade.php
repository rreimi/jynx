<div class="register-form-first" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    {{ Form::open(array('url' => 'registro','class'=>'big-form register-form', 'id' => 'register-form')) }}
        <h4 class='header'>{{ Lang::get('content.register_header') }}</h4>
        <fieldset>
            <div class="control-group {{ $errors->has('register_email')? 'error':'' }}">
                {{ Form::email('register_email',null,array('placeholder' => Lang::get('content.register_email'),'class' => 'input-block-level required email')) }}
            </div>
            <div class="control-group {{ $errors->has('register_full_name')? 'error':'' }}">
                {{ Form::text('register_full_name',null,array('placeholder' => Lang::get('content.register_full_name'),'class' => 'input-block-level required')) }}
            </div>
            <div class="control-group {{ $errors->has('register_password')? 'error':'' }}">
                {{ Form::password('register_password',array('id' => 'register_password', 'placeholder' => Lang::get('content.register_password'),'class' => 'input-block-level required')) }}
            </div>
            <div class="control-group {{ $errors->has('register_password_confirmation')? 'error':'' }}">
                {{ Form::password('register_password_confirmation',array('placeholder' => Lang::get('content.register_password_confirmation'),'class' => 'input-block-level required')) }}
            </div>
            <div class="checkbox terminos">
                {{ Form::checkbox('register_conditions',true,null,array('class'=>'required', 'data-placement' => 'right')) }} {{ Lang::get('content.register_conditions') }}
            </div>
        </fieldset>
    {{ Form::close() }}
</div>
<div class="register-form-conditions">
    <h2>TÉRMINOS Y CONDICIONES LEGALES</h2>
    <ol>
    <li class="main"><b>Condiciones generales</b></li>
    El presente sitio web tiene como fin primordial la promoción y difusión de productos y servicios ofrecidos por los empresarios, comerciantes y libres profesionales ligados a organizaciones ítalo-venezolanas, principalmente para el mercado y territorio de Venezuela.
    <br/>
    Las siguientes condiciones legales de uso del sitio aplicarán para todas las clases de usuarios que accedan a través de cualquier medio del entorno digital.
    <br/>
    <br/>
    <li class="main"><b>Objeto</b></li>
    El Sitio facilita a los Usuarios el acceso y la utilización de contenidos relacionados con la búsqueda de productos y servicios, así como ofertas de empleo realizadas por terceros USUARIO ANUNCIANTES que deseen promocionar sus productos y servicios y/o iniciar un proceso de búsqueda de personal de trabajo.
    <br/>
    <br/>
    <li class="main"><b>Modificaciones al Acuerdo</b></li>
    TUMERCATO se reserva el derecho a modificar unilateralmente, en cualquier momento y sin aviso previo, la presentación y configuración de El Sitio, así como de Los Servicios, los presentes Términos y Condiciones Generales y/o cualquieras otras condiciones y términos establecidas para utilizar El Sitio y Los Servicios. Se informará al usuario oportunamente mediante una notificación enviado al correo electrónico suministrado en el proceso de su registro.
    <br/>
    <br/>
    <li class="main"><b>Acceso y utilización del sitio</b></li>
    El acceso a El Sitio no exige la previa suscripción o registro de Los Usuarios. No obstante, la utilización de algunos Servicios que se ofrecen a través de El Sitio, sólo puede hacerse mediante suscripción o registro de El Usuario, en la forma en que se indica expresamente en las correspondientes Condiciones Particulares.
    <br/>
    El Usuario es consciente y acepta voluntariamente que el uso de El Sitio, de Los Servicios y de Los Contenidos tiene lugar, en todo caso, bajo su única y exclusiva responsabilidad.
    <br/>
    <br/>
    <li class="main"><b>Capacidad de uso</b></li>
    TUMERCATO es de libre navegación aunque requiere el cumplimiento de un proceso de registro para acceder a algunos de Los Servicios ofrecidos a través de El Sitio.  Así mismo, TUMERCATO pone a disposición de los Usuarios Anunciantes determinados Servicios cuya utilización requiere el registro y una solicitud consiguiente. Los datos suministrados en la solicitud serán validados con el fin de aprobar o rechazar el acceso a Los Servicios específicos para los Usuarios  Anunciantes.
    <br/>
    No podrán utilizar Los Servicios las personas que hayan sido suspendidos temporalmente o inhabilitados definitivamente
    <br/>
    <br/>
    <li class="main"><b>Clases de Usuarios</b></li>
    Para todos los efectos legales, toda persona que acceda a El Sitio a través de cualquier medio tecnológico será considerada un Usuario. No obstante, Los Usuarios serán clasificados de acuerdo a la actividad que desplieguen en El Sitio, de la forma siguiente:
    <br/>
    <br/>
    <ol class="internal">
    <li><b>Usuario anunciante</b></li>
    Es toda persona natural que en nombre propio o en representación de una persona jurídica, en su condición de miembro de CAVENIT o de algún club o asociación de la comunidad italiana en Venezuela con el cual El Sitio mantenga un contrato de servicios, habiendo cumplido el proceso previo de registro, ofrece de forma pública mediante los servicios de El Sitio, los productos y/o servicios a cuya comercialización se dedica.
    <br/>
    <br/>
    <li><b>Usuario registrado</b></li>
    Es toda persona natural que acceda a El Sitio y cumpla con el proceso de registro, mediante la inclusión de un nombre de usuario y una clave privada, por medio de la cual accederá siempre a éste para servirse de toda la información disponible para esta clase de usuarios presentada por los Usuarios Anunciantes.
    <br/>
    <br/>
    <li><b>Visitante</b></li>
    Es toda persona natural que acceda a través de cualquier medio tecnológico a El Sitio y navegue o recorra sus distintos espacios y secciones, con independencia de que cumpla o no el proceso de registro en el mismo.
    <br/>
    <br/>
    </ol>
    <li class="main"><b>Registro de usuarios</b></li>
    Es obligatorio completar el formulario de registro en todos sus campos con datos válidos para poder utilizar los servicios que brinda El Sitio, salvo lo dispuesto en el punto 6.3.
    <br/>
    TUMERCATO podrá emplear los medios que considere pertinentes para validar la información suministrada por sus Usuarios Registrados y Usuarios Anunciantes, sin embargo, no se responsabiliza por la certeza de los Datos provistos por los mismos.
    <br/>
    Los Usuarios garantizan y responden, en cualquier caso, de la veracidad, exactitud, vigencia y autenticidad de los Datos ingresados.
    <br/>
    <ol class="internal">
    <br/>
    <li><b>Registro de usuarios anunciantes</b></li>
    En el caso del Usuario Anunciante éste deberá completar el formulario con su información personal y/o de la empresa de manera exacta, precisa y verdadera ("Datos para Anunciantes") y asume el compromiso de actualizarlos conforme resulte necesario.
    <br/>
    El Sitio se reserva el derecho de solicitar algún comprobante y/o información adicional a efectos de corroborar los Datos para los Usuarios Anunciantes.
    <br/>
    <br/>
    <li><b>Asignación de Cuentas y contraseñas</b></li>
    Los Usuarios Registrados y Usuarios Anunciantes accederán a su Cuenta personal ("Cuenta") mediante el ingreso de su correo electrónico y una contraseña personal elegida ("Contraseña"). El Usuario se obliga a mantener la confidencialidad de su Contraseña. Toda Cuenta es de carácter personal, única e intransferible, y queda prohibida la inscripción de más de una cuenta bajo una misma dirección de correo electrónico, en cuyo caso, El Sitio podrá cancelar, suspender o inhabilitar una de las cuentas..
    <br/>
    <br/>
    <li><b>Uso y custodia de Cuentas y contraseñas</b></li>
    El Usuario será responsable por todas las operaciones efectuadas a través de su Cuenta, pues el acceso a la misma está restringido al ingreso y uso debido de su Contraseña, la cuál debe ser del conocimiento exclusivo del Usuario. El Usuario se compromete a notificar a El Sitio en forma inmediata y por medio fehaciente, cualquier uso no autorizado de su Cuenta, así como el ingreso por terceros no autorizados a la misma. Se aclara que está prohibida la venta, cesión o transferencia de La Cuenta bajo ningún título. TUMERCATO se reserva el derecho de rechazar cualquier solicitud de registro o de cancelar un registro previamente aceptado sin que ello genere algún derecho a indemnización o resarcimiento.
    <br/>
    </ol>
    <br/>
    <li class="main"><b>De los contenidos. Descripción de los Productos y Servicios</b></li>
    El Usuario se compromete a utilizar El Sitio y Los Servicios de conformidad con la ley, estas Condiciones Generales, las Políticas de Privacidad aplicables, así como con la moral, el orden público y buenas costumbres generalmente aceptadas en Venezuela.
    <br/>
    El Usuario se obliga a abstenerse de utilizar El Sitio y Los Servicios con fines ilícitos, contrarios a lo establecido en las leyes de la República y éstas Condiciones Generales, que puedan resultar lesivos de los derechos e intereses de terceros, o que de cualquier forma puedan dañar, inutilizar, sobrecargar o deteriorar El Sitio y Los Servicios o impedir la normal utilización o disfrute del Sitio y de Los Servicios por parte de los Usuarios.
    <br/>
    <br/>
    <ol class="internal">
    <li><b>Visualización de la información</b></li>
    Al publicar un anuncio en El Sitio, El Usuario Anunciante consiente expresamente la visualización y acceso por parte de los demás Usuarios Registrados y Visitantes, según el caso, la forma de promoción del producto por parte de El Sitio y de los servicios ofrecidos por éste.
    <br/>
    <br/>
    <li><b>Uso de la información</b></li>
    El Usuario se obliga a usar los Contenidos de forma diligente, correcta y lícita y, en particular, se compromete a abstenerse de (a) utilizar los Contenidos con fines contrarios a la ley, a la moral y a las buenas costumbres generalmente aceptadas o al orden público; (b) reproducir o copiar, distribuir, permitir el acceso del público a través de cualquier modalidad de comunicación pública, transformar o modificar Los Contenidos, a menos que se cuente con la autorización previa del titular de los correspondientes derechos o ello resulte legalmente permitido; (c) suprimir, eludir o manipular los Contenidos protegidos por los derechos de autor y los demás datos identificativos cuyos derechos correspondan a TUMERCATO o a los titulares incorporados a los Contenidos (productos y servicios anunciados), así como los dispositivos técnicos de protección, las huellas digitales o cualesquiera mecanismos de información que pudieren contener Los Contenidos.
    <br/>
    <br/>
    <li><b>Publicación de productos y/o servicios</b></li>
    El Usuario Anunciante deberá ofrecer los productos y/o servicios en las categorías y sub-categorías apropiadas, previamente definidas por El Sitio. Las publicaciones podrán incluir textos descriptivos, gráficos, fotografías y otros contenidos y condiciones pertinentes para la promoción del producto o del servicio, siempre que no violen ninguna disposición de este acuerdo o demás políticas de El Sitio. El producto ofrecido por el Usuario Anunciante debe ser minuciosamente descrito en cuanto a sus condiciones y características relevantes. La información de promoción de los productos y servicios podrá contener datos personales o de contacto tales como, y sin limitarse a: números telefónicos, dirección de e-mail, dirección postal, direcciones de páginas de Internet que contengan datos como los mencionados anteriormente, únicamente en los campos expresamente indicados por El Sitio para tales efectos.
    <br/>
    <br/>
    <li><b>Inclusión de imágenes y fotografías</b></li>
    El usuario puede incluir imágenes y fotografías del producto ofrecido siempre que las mismas se correspondan con el artículo, salvo que se trate de productos o de servicios que por su naturaleza no permiten esa correspondencia.
    <br/>
    <br/>
    <li><b>Inclusión de enlaces</b></li>
    Los Usuarios Anunciantes y, en general, aquellas personas que se propongan establecer un hiperenlace entre El Sitio y su página web (en adelante, el Enlace) deberán cumplir las condiciones siguientes: (a) el Enlace únicamente permitirá el acceso a las páginas web de su manejo o pertenencia, pero no podrá reproducirlas de ninguna forma dentro del mismo Sitio; (b) no se establecerán Enlaces con las páginas web de pertenencia a los Usuarios Anunciantes distintas de la home-page o página primera de su portal; (c) no se creará un browser ni un “border enviroment” (página paralela o espejo) sobre las páginas web del Portal; (d) no se realizarán manifestaciones o indicaciones falsas, inexactas o incorrectas sobre las páginas web del Sitio y los Servicios y, en particular, no se declarará ni dará a entender que TUMERCATO ha autorizado el Enlace o que ha supervisado o asumido de cualquier forma los contenidos o servicios ofrecidos o puestos a disposición en la página web a la que se establece el Enlace; (e) la página web a la que se establezca el Enlace no contendrá informaciones o contenidos ilícitos, contrarios a la moral y buenas costumbres generalmente aceptadas y al orden público, así como tampoco contendrá contenidos contrarios a cualesquiera derechos de terceros.
    <br/>
    El establecimiento del Enlace no implica en ningún caso la existencia de relaciones entre TUMERCATO y el propietario de la página web en la que se establezca, ni la aceptación y aprobación por parte de TUMERCATO de sus contenidos o servicios.
    <br/>
    <br/>
    <li><b>Prohibiciones</b></li>
    Los Usuarios no podrán: (a) Incluir los precios de los artículos; (b) publicar u ofrecer artículos prohibidos o en condiciones contrarias a lo previsto en los Términos y Condiciones Generales, demás políticas de El Sitio o leyes vigentes; (c) ofender o agredir a otros Usuarios.
    <br/>
    Este tipo de actividades será investigado por El Sitio y el infractor podrá ser sancionado con la suspensión o cancelación de la publicación incluso de su inscripción como Usuario de El Sitio y/o de cualquier otra forma que estime pertinente, sin perjuicio de las acciones legales a que pueda dar lugar por la configuración de delitos o contravenciones o los perjuicios civiles que pueda causar a Los Usuarios oferentes.
    <br/>
    <br/>
    <li><b>Artículos Prohibidos</b></li>
    Sólo podrán ser ingresados en las listas de productos y/o servicios ofrecidos, aquellos cuya venta y/o circulación no se encuentre tácita o expresamente prohibida en los Términos y Condiciones Generales y demás políticas de El Sitio o por la leyes vigentes de la República Bolivariana de Venezuela.
    <br/>
    <br/>
    <li><b>Protección de Propiedad Intelectual</b></li>
    El Usuario Anunciante se compromete y garantiza que los derechos de propiedad intelectual, patentes, signos distintivos y/o derechos de autor de los productos o servicios ofrecidos le pertenecen o, en su defecto,  cuentan con la autorización del respectivo titular. Queda entendido que El Sitio no se hace responsable por el mal uso que cualquier Usuario Anunciante pudiese hacer de derechos protegidos de propiedad intelectual.
    <br/>
    </ol>
    <br/>
    <li class="main"><b>Privacidad de la Información, Seguridad y Confidencialidad de datos personales</b></li>
    Para utilizar los Servicios ofrecidos por El Sitio, Los Usuarios deberán facilitar determinados datos de carácter personal. Su información personal se procesa y almacena en servidores o medios magnéticos que mantienen altos estándares de seguridad y protección tanto física como tecnológica.
    El sitio no venderá, alquilará ni negociará con otras empresas la información personal de Los Usuarios salvo en las formas y casos establecidas en las Políticas de Privacidad publicadas en El Sitio. El Sitio hará sus mejores esfuerzos para mantener la confidencialidad y seguridad de los Datos Personales de sus Usuarios.
    <br/>
    <br/>
    <li class="main"><b>Obligaciones de los Usuarios Anunciantes</b></li>
    El Usuario Anunciante debe tener capacidad legal para promocionar el producto o servicio ofrecido. Los Usuarios Anunciantes y Usuarios Registrados deben cumplir con todas las formalidades de su proceso de registro, suministrando la información solicitada y llenando apropiadamente todos los campos obligatorios.
    <br/>
    Los Usuarios Anunciantes deben cumplir con los Términos y Condiciones legales de El Sitio, así como todas las normas legales aplicables para la oferta y venta de productos y servicios en Venezuela.
    <br/>
    <br/>
    <li class="main"><b>Violaciones del Sistema o Bases de Datos</b></li>
    No está permitida ninguna acción o uso de dispositivo, software, u otro medio tendiente a interferir tanto en las actividades y operatoria de El Sitio como en las publicaciones, descripciones, Cuentas o bases de datos de El Sitio. Cualquier intromisión, tentativa o actividad violatoria o contraria a las leyes sobre derecho de propiedad intelectual y/o a las prohibiciones estipuladas en este contrato harán pasible a su responsable de las acciones legales pertinentes, y a las sanciones previstas por este acuerdo, así como lo hará responsable de indemnizar los daños ocasionados.
    <br/>
    <br/>
    <li class="main"><b>Sanciones. Suspensión de operaciones</b></li>
    Sin perjuicio de otras medidas, TUMERCATO podrá advertir, suspender en forma temporal o inhabilitar definitivamente La Cuenta de un Usuario o una publicación, iniciar las acciones que estime pertinentes y/o suspender la prestación de sus Servicios si (a) se quebrantara alguna ley, o cualquiera de las estipulaciones de los Términos y Condiciones Generales y demás políticas de El Sitio; (b) si se incurriera a criterio de El Sitio en conductas o actos dolosos o fraudulentos; (c) no pudiera verificarse la identidad de El Usuario Anunciante o cualquier información proporcionada por el mismo fuere errónea; (d) El sitio entendiera que las publicaciones u otras acciones pueden ser causa de responsabilidad para El Usuario que las publicó, para El Sitio o para Los Usuarios.
    <br/>
    TUMERCATO se reserva el derecho de suspender temporal o definitivamente a aquellos Usuarios Anunciantes cuyos datos no hayan podido ser confirmados. En estos casos de inhabilitación, se suspenderán todos los artículos publicados.
    <br/>
    En el caso de la suspensión o inhabilitación de un Usuario Anunciante, todos los artículos que tuviera publicados serán removidos del sistema.
    <br/>
    <br/>
    <li class="main"><b>Responsabilidad</b></li>
    El Sitio sólo pone a disposición de Los Usuarios una plataforma de promoción de productos y servicios, mediante un espacio virtual que les permite ponerse en comunicación para establecer relaciones directas de negocios entre éstas.  El Sitio no es responsable de ninguna negociación que pueda derivarse de la promoción de los productos y servicios contenidos en éste por cuanto no es el propietario de los artículos ofrecidos, no tiene posesión de ellos ni los ofrece en venta. El Sitio no interviene en el perfeccionamiento de las operaciones realizadas entre Los Usuarios ni en las condiciones por ellos estipuladas para las mismas, por lo cuál no será responsable respecto de la existencia, calidad, cantidad, estado, integridad o legitimidad de los productos ofrecidos, de la veracidad de los Datos Personales por ellos ingresados por Los Usuarios Anunciantes, o las consecuencias de los negocios jurídicos realizados por éstos. Cada Usuario Anunciante conoce y acepta ser el exclusivo responsable por los artículos y servicios que publica.
    <br/>
    <br/>
    <li class="main"><b>Alcance de los servicios del sitio</b></li>
    En virtud que El Sitio sólo pone a disposición de Los Usuarios una plataforma en línea de oferta de productos, servicios y oportunidades de empleo, mediante Internet, El Usuario reconoce y acepta que El Sitio no es parte en ninguna operación, ni tiene control alguno sobre la calidad, seguridad o legalidad de los artículos anunciados y/o servicios ofrecidos, la veracidad o exactitud de los anuncios, la capacidad de Los Usuarios Anunciantes para vender artículos. El Sitio no garantiza la veracidad de la publicidad de terceros que aparezca en El Sitio y no será responsable por la correspondencia o contratos que El Usuario celebre con dichos terceros o con otros Usuarios. Por otra parte, se aclara que este acuerdo no crea ningún contrato de sociedad, de mandato, de franquicia, o relación laboral entre El Sitio y El Usuario.
    <br/>
    <br/>
    <li class="main"><b>Garantías y exclusión de responsabilidad</b></li>
    <br/>
    <ol class="internal">
    <li><b>Continuidad de uso del sistema</b></li>
    En virtud que la plataforma prevista por El Sitio se basa en sistemas de software, hardware e Internet, cuya operatividad no depende de éste, por lo que no se puede garantizar el acceso y uso continuado o ininterrumpido de El Sitio.
    <br/>
    TUMERCATO no garantiza la disponibilidad y continuidad del funcionamiento del Sitio y de los Servicios. No todos los Servicios y Contenidos en general se encuentran disponibles para todas las áreas geográficas. Cuando ello sea razonablemente posible, TUMERCATO advertirá previamente las interrupciones en el funcionamiento del Sitio y de los Servicios. Se aclara que el sistema puede eventualmente no estar disponible debido a casos fortuitos o de fuerza mayor, así como dificultades técnicas o fallas de Internet, o por cualquier otra circunstancia ajena a El Sitio; en tales casos se procurará restablecerlo con la mayor celeridad posible sin que por ello pueda imputársele algún tipo de responsabilidad.
    <br/>
    <br/>
    <li><b>Utilidad y falibilidad del sitio</b></li>
    TUMERCATO tampoco garantiza la utilidad del Sitio y de los Servicios para la realización de ninguna actividad en particular, ni su infalibilidad y, en particular, aunque no de modo exclusivo, que los Usuarios puedan efectivamente utilizar el Sitio y los Servicios, acceder a las distintas páginas web que forman el Sitio o a aquéllas desde las que se prestan los Servicios.
    <br/>
    TUMERCATO excluye cualquier responsabilidad por los daños y perjuicios de toda naturaleza que puedan deberse a la falta de disponibilidad o de continuidad del funcionamiento del portal y de los servicios, a la defraudación de la utilidad que los usuarios hubieren podido atribuir al portal y a los servicios, a la falibilidad del portal y de los servicios, y en particular, aunque no de modo exclusivo, a las fallas en el acceso a las distintas páginas web del portal o a aquéllas desde las que se prestan los servicios.
    <br/>
    El Sitio no será responsable por ningún error u omisión contenidos en su sitio web. Los Usuarios no podrán imputarle responsabilidad alguna ni exigir pago por lucro cesante, en virtud de perjuicios resultantes de dificultades técnicas o fallas en los sistemas o en Internet.
    <br/>
    </ol>
    <br/>
    <li class="main"><b>Tarifas. Facturación</b></li>
    La inscripción en El Sitio es gratuita, al igual que la publicación informativa de los productos o servicios de Los Usuarios Anunciantes. El Sitio se reserva el derecho de establecer a futuro tarifas por el uso del servicio de publicación, lo cual será oportunamente notificado a Los Usuarios.
    <br/>
    <br/>
    <li class="main"><b>Propiedad intelectual</b></li>
    Los contenidos de las pantallas relativas a los servicios de El Sitio como así también los programas, bases de datos, redes, archivos que permiten al Usuario acceder y usar su Cuenta, así como el nombre de dominio del El Sitio, son de su exclusiva propiedad y están protegidas por las leyes y los tratados internacionales de derecho de autor, marcas, patentes, modelos y diseños industriales. El uso indebido y la reproducción total o parcial de dichos contenidos quedan prohibidos, salvo autorización expresa y por escrito de El Sitio.
    <br/>
    <br/>
    <li class="main"><b>Capacidad para aceptar las condiciones del servicio</b></li>
    Este sitio web está dirigido sólo a mayores de 18 años. Al aceptar los Términos y Condiciones, usted afirma que es mayor de 18 años de edad o un menor emancipado o que posee consentimiento legal de sus padres o representantes, y es completamente capaz y competente para entrar en lo establecido en estos términos, y de acatar y cumplir con estos mismos. En cualquier caso, usted afirma que usted es mayor de 13 años, ya que el sitio web no está dirigido a menores de 13 años.
    <br/>
    <b>AVISO A LOS NIÑOS MENORES DE 13 AÑOS Y SUS PADRES O REPRESENTANTES</b>
    <br/>
    Si usted es menor de 13 años, NO DEBE UTILIZAR ESTE SITIO WEB. Por favor no nos envíe su información personal, incluyendo direcciones de correo electrónico, nombre y / o información de contacto. Si quieres contactar con nosotros, sólo puede hacerlo a través de su padre o representante legal.
    <br/>
    <br/>
    <li class="main"><b>Domicilio</b></li>
    Se fija como domicilio de El Sitio el siguiente:
    Avenida Principal, Edificio D’Ambrosio Hermanos, Piso 1 Oficina 1, Urbanización Colinas de Bello Monte, Caracas, Venezuela, 1050.
    <br/>
    <br/>
    <li class="main"><b>Duración y terminación</b></li>
    La prestación del servicio de TUMERCATO y de los demás Servicios tiene, en principio, una duración indefinida. TUMERCATO, no obstante, está facultada para dar por terminada o suspender la prestación del servicio del Sitio y/o de cualquiera de los Servicios en cualquier momento.  Cuando ello sea razonablemente posible, TUMERCATO advertirá previamente la terminación o suspensión de la prestación del servicio del Sitio y de los demás Servicios.
    <br/>
    <br/>
    <li class="main"><b>Jurisdicción y Ley Aplicable</b></li>
    Este acuerdo se regirá en todas sus partes por las leyes vigentes en la República Bolivariana de Venezuela.
    <br/>
    Cualquier controversia derivada del presente acuerdo, su existencia, validez, interpretación, alcance o cumplimiento, será sometida a las leyes aplicables y a los Tribunales competentes de la República Bolivariana de Venezuela y los procedimientos se llevarán a cabo en idioma castellano.
    <br/>
    <br/>
    </ol>
    <a noref onclick="javascript:hideConditions();" class="manito">{{ Lang::get('content.register_hide_conditions_back') }}</a>
    <br/>
    <br/>
</div>

@section('scripts')
@parent
<script type="text/javascript">
    function showConditions(){
        jQuery('.register-form-first').hide();
        jQuery('.modal-header').hide();
        jQuery('.modal-footer').hide();
        jQuery('.register-form-conditions').show();
    }

    function hideConditions(){
        jQuery('.register-form-conditions').hide();
        jQuery('.register-form-first').show();
        jQuery('.modal-header').hide();
        jQuery('.modal-footer').show();
    }
</script>
@stop