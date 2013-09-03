<?php
class lang{
	// var lang;
	function lang($lang='en'){
		switch($lang){
			case'en':{
				$lng = array(
					'home'=>'Controlled easily',
					'projects'=>'projects',
					'Projects'=>'Projects',
					'clients'=>'clients',
					'Clients'=>'Clients',
					'tasks'=>'tasks',
					'Tasks'=>'Tasks',
					'task'=>'task',
					'Task'=>'Task',
					'sites'=>'sites',
					'Sites'=>'Sites',
					'portfolio'=>'portfolio',
					'Portfolio'=>'Portfolio',
					'orders'=>'orders',
					'Orders'=>'Orders',
					'finance'=>'finance',
					'Finance'=>'Finance',
					'on sum'=>'to the amount of',
					'makeup'=>'Makeup',
					'balance'=>'Balance',
					'done'=>'Done',
					'time expired'=>'Time is up',
					'add'=>'Add',
					'added'=>'added',
					'Added'=>'Added',
					'added operation'=>'Added operation',
					'add finance operation'=>'Add a financial transaction',
					'insert'=>'Insert',
					'user'=>'User',
					'users'=>'Users',
					'news'=>'News',
					'archive'=>'Archive',
					'new news'=>'news',
					'add news'=>'Add news',
					'delete news'=>'Delete news',
					'faq'=>'FAQ',
					'faq text'=>'<ul class="d-n">
									<li>1. <a href="#main">Основные положения</a></li>
									<li>2. <a href="#payments">Оплата</a></li>
								</ul>
								<a name="main"></a>
								<h2>1. Основные положения</h2>
									<p>Система (сервис) создана для уменьшения времени, затрачиваемого на ведение бизнеса (нескольких бизнесов). Предоставляются такие функциональные возможности как: упраление проектами, управление заданиями, аналитика бюджета и прочее.</p>
									<p>Все обновления публикуются в новостях.</p>
									<p>Время сервиса московское.</p>
								<a name="payments"></a>
								<h2>2. Оплата</h2>
									Её нет:)',
					'contacts'=>'contacts',
					'Contacts'=>'Contacts',
					'contacts text'=>'<p>If You have any questions about:</p>
								<ol>
									<li>service work;</li>
									<li>partnership;</li>
									<li>donation.</li>
								</ol>
								<p>Email: dev@asdat.org</p>',
					'testing'=>'Testing',
					'subscribe'=>'Subscribe',
					'designs'=>'Layout design sites',
					'check domains'=>'Domain name check',
					'date'=>'Date',
					'title'=>'Title',
					'short description'=>'Short description',
					'full description'=>'Full description',
					'not public'=>'Do not publish',
					'remove from publication'=>'Remove from the publication',
					'public'=>'Publish',
					'settings'=>'Settings',
					'logout'=>'Logout',
					'ru'=>'Russian',
					'en'=>'English',
					'valuteconverter'=>'Valute converter',
					'Add transaction'=>'Add transaction',
					'Error! Please check the fields are correct'=>'<strong>Error!</strong> Please check the fields are correct',
					'Cancel'=>'Cancel',
					'added performer'=>'Added performer',
					'added position'=>'Added a new position',
					'added user'=>'Added user',
					'to position'=>'to position',
					'with salary'=>'with salary',
					'add user to position'=>'Add user to position',
					'position'=>'Position',
					'Salary'=>'Salary',
					'Level'=>'Level',
					'Changed'=>'Changed',
					'saved'=>'saved',
					'Saved'=>'Saved',
					'New client'=>'New client',
					'Add client or contractor'=>'Add client or contractor',
					'Name'=>'Name',
					'Type'=>'Type',
					'Contacts'=>'Contacts',
					'Add project'=>'Add project',
					'Client'=>'Client',
					'- New -'=>'- New -',
					'Name of client or organisation'=>'Name of client or organisation',
					'Project name'=>'Project name',
					'Project priority'=>'Project priority',
					'Valute'=>'Valute',
					'In'=>'In',
					'Out'=>'Out',
					'Deadline'=>'Deadline',
					'Additional information'=>'Additional information',
					'Add task'=>'Add task',
					'Cost'=>'Cost',
					'End date'=>'End date',
					'Project &rarr; old task'=>'Project &rarr; old task',
					'Name'=>'Name',
					'Added a new task'=>'Added a new task',
					'items'=>'items',
					'New'=>'New',
					'Old'=>'Old',
					'Add files'=>'Add files',
					'Display numbers'=>'Display numbers',
					'Add'=>'Add',
					'You was stuck to the'=>'You was stuck to the',
					'Go to'=>'Go to',
					'task'=>'task',
					'project'=>'project',
					'task item'=>'task item',
					'Add a performer to the'=>'Add a performer to the',
					'Balance'=>'Balance',
					'Finance'=>'Finance',
					'Projects'=>'Projects',
					'Invoice'=>'Invoice',
					'Graph'=>'Graph',
					'Regular transactions'=>'Regular transactions',
					'Transactions'=>'Transactions',
					'Users'=>'Users',
					'Orders'=>'Orders',
					'Apply'=>'Apply',
					'Clients'=>'Clients',
					'Contractors'=>'Contractors',
					'Tasks Clients'=>'Tasks Clients',
					'Archive'=>'Archive',
					'Select all'=>'Select all',
					'Deselect'=>'Deselect',
					'Attached projects'=>'Attached projects',
					'Own projects'=>'Own projects',
					'Archive Projects'=>'Archive Projects',
					'Closed Projects'=>'Closed Projects',
					'Done'=>'Done',
					'In progress'=>'In progress',
					'Paused'=>'Paused',
					'Need to discuss'=>'Need to discuss',
					'Not in progress'=>'Not in progress',
					'Language'=>'Language',
					'Terms'=>'Terms',
					'Terms text'=>'<h3>1. Basic Concepts</h3>
						<p>site - a site located on the Internet at <a href="https://whm.asdat.biz/">https://whm.asdat.biz/</a>.<br />Developer - natural or legal person who places the application on the Website and use it in accordance with the Conditions and Rules of Application Hosting Application Hosting on the Site. Last name, first name or the name of the Developer, as well as other information about it, refer to "Developer Information" in the Application Launcher and Application Settings.<br />Site Administration - PL-P Datsko MS, registration number 989253.<br />application - a software service deployed on the developer website under "Applications" held on the moderation of the site and included in the application directory.< br />User - the user of the website, registered in the prescribed manner and using the application.</p>
						<h3>2. Status of the Rules</h3>
						<dl><dd>2.1. These Terms constitute an agreement between the developer and the user (hereinafter collectively referred to as the "Parties"), and regulate the rights and obligations of developers and users in the use of applications.</dd></dl><dl><dd>2.2. These Rules are the official standard document Developers. These Regulations apply to these relations, if developer is not approved and is not applying its own rules of services referred to in the box provided users run applications and application settings.</dd></dl><dl><dd>2.3. The current edition of the Regulations, a public document, developed by the Site Administration, and is available to any user of the Internet when you click on a hypertext link "RulesÂ».</dd></dl><dl><dd>Site Administration has the right to amend these rules. When you make changes to the Rules of the Site Administration shall notify users by posting a new version of the Rules on the Site`s permanent address <a href="https://whm.asdat.biz/terms.html">https://whm.asdat.biz/terms.html</a>, not later than [10] days before the entry into force of the amendments. Previous editions of the Rules are stored in the archive documents of the Site Administration.</dd></dl><dl><dd>2.3. The provisions of this Regulation are considered as a public offer in accordance with<span>st.633 Civil Code of Ukraine (CCU). User shall be fully acquainted with these Rules before the first run the application. Starting the application, the user is a complete and unconditional acceptance by the User of this Regulation in accordance with st.633 Civil Code of Ukraine (CCU). The provisions of this Regulation may only be taken as a whole, without any exceptions.</span></dd></dl><dl><dd>2.4. Reference to this Regulation after the application is available at the bottom of the application. User shall from time to time to check the current version of this Regulation in the bottom of the applications for amendments and / or additions. Continued use of the user`s application of the entry into force of the amendments to this Regulation constitutes acceptance of the User agreement and subject to such modifications and/or additions.</dd></dl>
						<h3>3. The rights and obligations of the Parties</h3>
						<dl><dd>3.1. User is required to read the information about the developer, Developer Privacy Policy and these Rules to first run the application. In case of disagreement with the provisions of the said documents User is obliged to refrain from launching and using applications.</dd></dl><dl><dd>3.2. User agrees to use application for your personal use. Prohibited from offering services related to the use of the application, other users for profit. Prohibited to use any automated scripts ("Bots"), or other means to interact with the application without user intervention. Forbidden to perform any act aimed at the disruption of the normal functioning of the applications, and the use of special programs containing harmful components ("virusesÂ»).</dd></dl><dl><dd>3.3. Developer shall have the right at any time to make changes in the functional applications, interface and / or the content of applications with or without notice thereof.</dd></dl><dl><dd>3.4. The developer has the right to unilaterally set the cost of certain services offered by the application, expressed in local currency Applications - KOINS. User agrees to use good faith ways to fund a personal account to log into and further use KOINS. The developer of the facts when it detects unauthorized deposit to the personal account to log into Developer shall have the right to refuse the User in the future provision of services or unilaterally reduce personal account to log into.</dd></dl><dl><dd>3.5. The developer has the right to request and use information about the User solely for the provision of services for your applications. Use of the information about the user privacy policies Developer.</dd></dl><dl><dd>3.6. The developer is required to provide technical support for applications and provide an easy way of communication for Users on all questions arising in the use of applications on a "Contact".</dd></dl>
						<h3>4. Intellectual property</h3>
						<dl><dd>4.1. User acknowledges that the application, its interface and content (including, but not limited to, design elements, text, graphics, images, video, scripts, programs, music, sounds and other objects and collections related to the Appendix) are protected by copyright , trade marks, patents and other rights that belong to the developer or other respective owners.</dd></dl><dl><dd>4.2. Developer grants the User a non-exclusive license to use the Application, namely the launch and continued operation of applications solely for the purpose of satisfying personal, family, household or other non-business activities needs, without the right to transfer the license to third parties, and without the right to grant sublicenses to use the Application third parties.</dd></dl><dl><dd>4.3. User may not reproduce, copy, alter, destroy, recycle (including the implementation of any translation or localization), sell, rent, publish, download, otherwise distribute the application or its components, decompile or otherwise attempt to derive the source code of components Applications is a software and change the functionality of applications without the prior written consent of the Developer.</dd></dl><dl><dd>4.4. User may not remove and / or modify any of the information posted in the Developer Application, including copyright notices and means of individualization.</dd></dl><dl><dd>4.5. The license set forth in paragraph 4.2 of this Regulation granted for the duration of use of the user`s application. This license also covers all the updates and/or additional application components that can be created and made available to developers in the future.</dd></dl><dl><dd>4.6. Except as otherwise expressly provided in this Regulation, nothing in these Regulations shall be construed as a transfer of exclusive rights to the application and/or its components to the user.</dd></dl>
						<h3>5. Guarantees and Responsibilities</h3>
						<dl><dd>5.1. User acknowledges and agrees that the application is provided "as is". The developer does not provide guarantees in respect of the consequences of the use of applications, application interaction with other software.</dd></dl><dl><dd>5.2. The developer does not provide assurance that the application may be suitable for the particular purpose of use. User acknowledges and agrees that the result of the use of the application can not meet User`s expectations.</dd></dl><dl><dd>5.3. The developer or other copyright holders under any circumstances be held liable for any indirect, incidental, unintentional injuries (including loss of profits, damage caused by the loss of data), caused by the use of applications or inability to use it, including the failure of the Application or other interruption in the use of applications, even if the developer warned or mentioned the possibility of such damages.</dd></dl><dl><dd>5.4. The user is solely responsible for his actions on the use of applications, including those for the actions of the placement and transfer of information, comments, images and other materials to other users using the application. The user is solely responsible for the observance of the rights of third parties, the applicable law, these Rules, any rules and/or mandatory instructions Developer, located in the "Help" Applications using the Application.</dd></dl><dl><dd>5.5. For violations committed by the User, Developer shall have the right to refuse the User in the future provision of services or limit such provision in whole or in part with or without notice thereof.</dd></dl>
						<h3>6. Final Provisions</h3>
						<dl><dd>6.1. The user may at any time refuse to provide services through the removal of Annex with his personal page on the Website.</dd></dl><dl><dd>6.2. The developer may at any time suspend or terminate the operation of applications with or without notice thereof.</dd></dl><dl><dd>6.3. These Terms are governed by and construed in accordance with the laws of Ukraine. Issues not regulated by the Rules shall be settled in accordance with the laws of Ukraine.</dd></dl><dl><dd>6.4. In the event of any dispute or disagreement related to the execution of these Regulations, developers and users will make every effort to resolve them through negotiations between them. If disputes can not be settled by negotiation, shall be resolved in accordance with the current legislation of Ukraine.</dd></dl><dl><dd>6.5. These rules are written in Russian, and may be provided to the user for information in another language. In case of divergence of the Russian version of the Rules and Regulations version in another language, the provisions of the Russian version of this Regulation.</dd></dl><dl><dd>6.6. If for any reason one or more provisions of these Rules will be declared invalid or void, this shall not affect the validity or enforceability of the remaining provisions.</dd></dl>',
					'Help'=>'Help',
					'Help text'=>'<ul class="faq">
						<li><a href="#start">Start</a></li>
						<li><a href="#menu">Menu</a></li>
						<li><a href="#sections">Sections</a></li>
						<li><a href="#projects">Projects</a>
							<ul>
								<li><a href="#addproject">Add Project</a></li>
								<li><a href="#task">Task Items</a></li>
							</ul>
						</li>
						<li><a href="#finance">Finance</a>
							<ul>
								<li><a href="#addtransaction">Add Transaction</a></li>
								<li><a href="#financeanalytics">Finance Analytics</a></li>
							</ul>
						</ul>
						<p><a name="start"></a><h2>Start</h2><img src="img/faq/home.png" width="700" /></p>
						<p><a name="menu"></a><h2>Menu</h2><img src="img/faq/menu.png" width="700" /></p>
						<p><a name="sections"></a><h2>Sections</h2><img src="img/faq/section.png" width="700" /></p>
						<p><a name="projects"></a><h2>Projects</h2><img src="img/faq/projects.png" width="700" /></p>
						<p><a name="projects"></a><h3>Add Project</h3><img src="img/faq/addproject.png" width="700" /></p>
						<p><a name="task"></a><h3>Task Items</h3><img src="img/faq/taskitems.png" width="700" /></p>
						<p><a name="finance"></a><h2>Finance</h2><img src="img/faq/finance.png" width="700" /></p>
						<p><a name="addtransaction"></a><h3>Add Transaction</h3><img src="img/faq/addtransaction.png" width="700" /></p>
						<p><a name="financeanalytics"></a><h3>Finance Analytics</h3><img src="img/faq/fanalytics.png" width="700" /></p>',
					'Add a task items'=>'Add a task items',
					'Settings'=>'Settings',
					'Home'=>'Home',
					'Projects'=>'Projects',
					'Clients and contractors'=>'Clients and contractors',
					'Finance'=>'Finance',
					'Makeup'=>'Makeup',
					'Users'=>'Users',
					'Logout'=>'Logout',
					'Archive'=>'Archive',
					'Sites'=>'Sites',
					'Login'=>'Login',
					'Public'=>'Public',
					''=>'',
					''=>''
					);
				break;
			}
			case'ru':{
				$lng = array(
					'home'=>'Управляй легко',
					'projects'=>'проекты',
					'Projects'=>'Проекты',
					'clients'=>'клиенты',
					'Clients'=>'Клиенты',
					'tasks'=>'задания',
					'Tasks'=>'Задания',
					'task'=>'задание',
					'Task'=>'Задание',
					'sites'=>'сайты',
					'Sites'=>'Сайты',
					'portfolio'=>'портфолио',
					'Portfolio'=>'Портфолио',
					'orders'=>'заказы',
					'Orders'=>'Заказы',
					'finance'=>'финансы',
					'Finance'=>'Финансы',
					'on sum'=>'to the amount of',
					'makeup'=>'Верстка',
					'balance'=>'Баланс',
					'done'=>'Готово',
					'time expired'=>'Время вышло',
					'add'=>'Добавить',
					'added'=>'добавлено',
					'Added'=>'Добавлено',
					'added operation'=>'Операция добавлена',
					'add finance operation'=>'Добавить финансовую операцию',
					'insert'=>'Вставить',
					'user'=>'пользователь',
					'users'=>'пользователи',
					'news'=>'новости',
					'News'=>'Новости',
					'archive'=>'архив',
					'new news'=>'новые новости',
					'add news'=>'добавить новости',
					'delete news'=>'удалить новости',
					'faq'=>'ЧАВО',
					'faq text'=>'<ul class="d-n">
									<li>1. <a href="#main">Основные положения</a></li>
									<li>2. <a href="#payments">Оплата</a></li>
								</ul>
								<a name="main"></a>
								<h2>1. Основные положения</h2>
									<p>Система (сервис) создана для уменьшения времени, затрачиваемого на ведение бизнеса (нескольких бизнесов). Предоставляются такие функциональные возможности как: упраление проектами, управление заданиями, аналитика бюджета и прочее.</p>
									<p>Все обновления публикуются в новостях.</p>
									<p>Время сервиса московское.</p>
								<a name="payments"></a>
								<h2>2. Оплата</h2>
									Её нет:)',
					'contacts'=>'контакты',
					'Contacts'=>'Контакты',
					'contacts text'=>'<p>Если у Вас есть какие-либо вопосы:</p>
								<ol>
									<li>работа сайта;</li>
									<li>сотрудничество;</li>
									<li>благотворительность.</li>
								</ol>
								<p>Email: dev@asdat.org</p>',
					'testing'=>'тестирование',
					'subscribe'=>'подписка',
					'Subscribe'=>'Подписка',
					'designs'=>'Верстка дизайна сайтов',
					'check domains'=>'проверка доменного имени',
					'date'=>'дата',
					'title'=>'название',
					'short description'=>'краткое описание',
					'full description'=>'полное описание',
					'not public'=>'не публиковать',
					'remove from publication'=>'убрать с публикации',
					'public'=>'опубликовать',
					'settings'=>'настройки',
					'logout'=>'выйти',
					'ru'=>'русский',
					'en'=>'английский',
					'valuteconverter'=>'конвертер валют',
					'Add transaction'=>'Добавить операцию',
					'Error! Please check the fields are correct'=>'<strong>Ошибка!</strong> Пожалуйста, проверьте правильность заполнения полей',
					'Cancel'=>'Отмена',
					'added performer'=>'Добавлен сотрудник',
					'added position'=>'Добавлена новая должность',
					'added user'=>'Добавлен пользователь',
					'to position'=>'к должности',
					'with salary'=>'с зарплатой',
					'add user to position'=>'Добавить пользователя к должности',
					'position'=>'Должность',
					'Salary'=>'Зарплата',
					'Level'=>'Уровень',
					'Changed'=>'Изменено',
					'saved'=>'сохранено',
					'Saved'=>'Сохранено',
					'New client'=>'Новый клиент',
					'Add client or contractor'=>'Добавить клиента/контрагента',
					'Name'=>'Имя',
					'Type'=>'Тип',
					'Contacts'=>'Контакты',
					'Add project'=>'Добавить проект',
					'Client'=>'Клиент',
					'- New -'=>'- Новый -',
					'Name of client or organisation'=>'Имя клиента или организации',
					'Project name'=>'Имя проекта',
					'Project priority'=>'Приоритетность',
					'Valute'=>'Валюта',
					'In'=>'Доход',
					'Out'=>'Расход',
					'Deadline'=>'Дата окончания',
					'Additional information'=>'Дополнительная информация',
					'Add task'=>'Добавить задание',
					'Cost'=>'Стоимость',
					'End date'=>'Конечная дата',
					'Project &rarr; old task'=>'Проект &rarr; существующее задание',
					'Name'=>'Имя',
					'Added a new task'=>'Добавлено новое задание',
					'items'=>'пункты',
					'New'=>'Новое',
					'Old'=>'Существующее',
					'Add files'=>'Добавить файлы',
					'Display numbers'=>'Отображать номера',
					'Add'=>'Добавить',
					'You was stuck to the'=>'Вы были прикреплены',
					'Go to'=>'Перейдите сюда',
					'task'=>'заданию',
					'project'=>'проекту',
					'task item'=>'пункту задания',
					'Add a performer to the'=>'Добавить сотрудника к',
					'Balance'=>'Баланс',
					'Finance'=>'Финансы',
					'Projects'=>'Проекты',
					'Invoice'=>'Счета',
					'Graph'=>'График',
					'Regular transactions'=>'Регулярные операции',
					'Transactions'=>'Операции',
					'Users'=>'Пользователи',
					'Orders'=>'Заказы',
					'Apply'=>'Применить',
					'Clients'=>'Клиенты',
					'Contractors'=>'Контрагенты',
					'Tasks Clients'=>'Задания клиентов',
					'Archive'=>'Архив',
					'Select all'=>'Выбрать все',
					'Deselect'=>'Отменить выбор',
					'Attached projects'=>'Прикрепленные проекты',
					'Own projects'=>'Личные проекты',
					'Archive Projects'=>'Архивные проекты',
					'Closed Projects'=>'Закрытые проекты',
					'Done'=>'Выполено',
					'In progress'=>'В процессе',
					'Paused'=>'На паузе',
					'Need to discuss'=>'Нужно обсудить',
					'Not in progress'=>'Не начиналось',
					'Language'=>'Язык',
					'Terms text'=>'<h3>1. Основные понятия</h3>
						<p>Сайт – сайт, расположенный в сети Интернет по адресу <a href="https://whm.asdat.biz/">https://whm.asdat.biz/</a>. <br />Разработчик – физическое или юридическое лицо, размещающее приложение на Сайте и использующее его в соответствии с Условиями размещения приложений и Правилами размещения приложений на Сайте. Фамилия, имя и отчество либо наименование Разработчика, а также иная информация о нем указаны в разделе «Информация о разработчике» в окне запуска Приложения и в настройках Приложения. <br />Администрация Сайта – ФЛ-П Дацько М.С., регистрационный номер 989253.<br />Приложение – программный сервис, размещенный Разработчиком на Сайте в разделе «Приложения», прошедший модерацию Администрацией Сайта и включенный в каталог приложений.<br />Пользователь – пользователь Сайта, зарегистрированный в установленном порядке и использующий Приложение.</p>
						<h3>2. Статус Правил</h3>
						<dl><dd>2.1. Настоящие Правила представляют собой соглашение между Разработчиком и Пользователем (далее вместе именуемые «Стороны») и регулируют права и обязанности Разработчика и Пользователя в связи с использованием Приложения.</dd></dl><dl><dd>2.2. Настоящие Правила являются официальным типовым документом Разработчиков. Настоящие Правила применяются к указанным отношениям в случае, если Разработчик не утвердил и не применяет свои собственные Правила оказания услуг, ссылка на которые предоставляется Пользователям в окне запуска Приложения и в настройках Приложения.</dd></dl><dl><dd>2.3. Действующая редакция Правил, являющихся публичным документом, разработана Администрацией Сайта и доступна любому пользователю сети Интернет при переходе по гипертекстовой ссылке «Правила».</dd></dl><dl><dd>Администрация Сайта вправе вносить изменения в настоящие Правила. При внесении изменений в Правила Администрация Сайта уведомляет об этом пользователей путем размещения новой редакции Правил на Сайте по постоянному адресу <a href="https://whm.asdat.biz/terms.html">https://whm.asdat.biz/terms.html</a> не позднее, чем за [10] дней до вступления в силу соответствующих изменений. Предыдущие редакции Правил хранятся в архиве документации Администрации Сайта.</dd></dl><dl><dd>2.3. Положения настоящих Правил рассматриваются как публичная оферта в соответствии со <span>ст.633 Гражданского кодекса Украины (ГКУ). Пользователь обязан полностью ознакомиться с настоящими Правилами до первого запуска Приложения. Запуск Приложения Пользователем означает полное и безоговорочное принятие Пользователем настоящих Правил в соответствии со ст.633 Гражданского кодекса Украины (ГКУ). Положения настоящих Правил могут быть приняты только в целом без каких-либо изъятий.</span></dd></dl><dl><dd>2.4. Ссылка на настоящие Правила после запуска Приложения доступна в нижней части приложения. Пользователь обязан время от времени проверять текущую версию настоящих Правил в нижней части Приложения на предмет внесения изменений и/или дополнений. Продолжение использования Приложения Пользователем после вступления в силу соответствующих изменений настоящих Правил означает принятие и согласие Пользователя с такими изменениями и/или дополнениями.</dd></dl>
						<h3>3. Права и обязанности Сторон</h3>
						<dl><dd>3.1. Пользователь обязан ознакомиться с информацией о Разработчике, политикой конфиденциальности Разработчика и настоящими Правилами до первого запуска Приложения. При несогласии с положениями указанных документов Пользователь обязан воздержаться от запуска и использования Приложения.</dd></dl><dl><dd>3.2. Пользователь обязуется использовать Приложение в личных некоммерческих целях. Запрещается предлагать услуги, связанные с использованием Приложения, другим Пользователям в целях извлечения прибыли. Запрещается использование каких-либо автоматических скриптов («программы-роботы») или иных средств, позволяющих взаимодействовать с Приложением без участия Пользователя. Запрещается совершать действия, направленные на нарушение нормального функционирования Приложения, и использовать специальные программы, содержащие вредоносные компоненты («вирусы»).</dd></dl><dl><dd>3.3. Разработчик вправе в любое время вносить изменения в функционал Приложения, интерфейс и/или содержание Приложения с уведомлением Пользователей или без такового.</dd></dl><dl><dd>3.4. Разработчик вправе в одностороннем порядке устанавливать стоимость отдельных сервисов, предлагаемых Приложением, выраженную во внутренней валюте Приложений - коинсах. Пользователь обязуется добросовестно использовать способы пополнения личного счета Пользователя в Приложении и дальнейшего использования коинсов. При обнаружении Разработчиком фактов неправомерного пополнения личного счета Пользователя в Приложении Разработчик вправе отказать Пользователю в дальнейшем предоставлении услуг либо в одностороннем порядке уменьшить личный счет Пользователя в Приложении.</dd></dl><dl><dd>3.5. Разработчик вправе запросить и использовать информацию о Пользователе исключительно в целях предоставления услуг по использованию Приложения. Использование информации о Пользователе регулируется политикой конфиденциальности Разработчика.</dd></dl><dl><dd>3.6. Разработчик обязан обеспечить техническую поддержку Приложения и предоставить простой способ связи для обращений Пользователей по всем возникающим в процессе использования Приложения вопросам в разделе «Контакты».</dd></dl>
						<h3>4. Интеллектуальная собственность</h3>
						<dl><dd>4.1. Пользователь признает, что Приложение, его интерфейс и содержание (включая, но не ограничиваясь, элементы дизайна, текст, графические изображения, иллюстрации, видео, скрипты, программы, музыка, звуки и другие объекты и их подборки, связанные с Приложением) защищены авторским правом, товарными знаками, патентами и иными правами, которые принадлежат Разработчику или иным законным правообладателям.</dd></dl><dl><dd>4.2. Разработчик предоставляет Пользователю неисключительную лицензию на использование Приложения, а именно на запуск и дальнейшую эксплуатацию Приложения исключительно в целях удовлетворения личных, семейных, домашних или иных не связанных с предпринимательской деятельностью нужд, без права передачи данной лицензии третьим лицам и без права предоставления сублицензий на использование Приложения третьим лицам.</dd></dl><dl><dd>4.3. Пользователь не вправе воспроизводить, копировать, изменять, уничтожать, перерабатывать (включая выполнение любого перевода или локализации), продавать, сдавать в прокат, опубликовывать, скачивать, иным образом распространять Приложение либо его компоненты, декомпилировать или иным образом пытаться извлечь исходный код компонентов Приложения, являющихся программным обеспечением, а также изменять функционал Приложения без предварительного письменного согласия Разработчика.</dd></dl><dl><dd>4.4. Пользователь не вправе удалять и/или изменять какую-либо информацию, размещенную Разработчиком в рамках Приложения, в том числе знаки охраны авторского права и средств индивидуализации.</dd></dl><dl><dd>4.5. Лицензия, указанная в пункте 4.2 настоящих Правил, предоставляется на весь срок использования Приложения Пользователем. Данная лицензия распространяется также на все обновления и/или дополнительные компоненты Приложения, которые могут быть созданы и предоставлены Разработчиком в будущем.</dd></dl><dl><dd>4.6. Если иное явным образом не установлено в настоящих Правилах, ничто в настоящих Правилах не может быть рассмотрено как передача исключительных прав на Приложение и/или его компоненты Пользователю.</dd></dl>
						<h3>5. Гарантии и Ответственность</h3>
						<dl><dd>5.1. Пользователь признает и соглашается с тем, что Приложение предоставляется на условиях «как есть». Разработчик не предоставляет гарантий в отношении последствий использования Приложения, взаимодействия Приложения с другим программным обеспечением.</dd></dl><dl><dd>5.2. Разработчик не предоставляет гарантий того, что Приложение может подходить для конкретных целей использования. Пользователь признает и соглашается с тем, что результат использования Приложения может не соответствовать ожиданиям Пользователя.</dd></dl><dl><dd>5.3. Разработчик или иные правообладатели ни при каких обстоятельствах не несут ответственность за любой косвенный, случайный, неумышленный ущерб (включая упущенную выгоду, ущерб, причиненный утратой данных), вызванный в связи с использованием Приложения или невозможностью его использования, в том числе в случае отказа работы Приложения или иного перерыва в использовании Приложения, даже если Разработчик предупреждал или указывал на возможность такого ущерба.</dd></dl><dl><dd>5.4. Пользователь самостоятельно несет ответственность за свои действия по использованию Приложения, в том числе за действия по размещению и передаче информации, комментариев, изображений и иных материалов другим Пользователям с помощью Приложения. Пользователь самостоятельно несет ответственность за соблюдение прав третьих лиц, применимого законодательства, настоящих Правил, каких-либо правил и/или обязательных инструкций Разработчика, размещенных в разделе «Помощь» Приложения, при использовании Приложения.</dd></dl><dl><dd>5.5. За нарушения, допущенные Пользователем, Разработчик вправе отказать Пользователю в дальнейшем предоставлении услуг или ограничить такое предоставление полностью или частично с уведомлением Пользователя или без такового.</dd></dl>
						<h3>6. Заключительные положения</h3>
						<dl><dd>6.1. Пользователь вправе в любой момент отказаться от предоставления услуг посредством удаления Приложения со своей персональной страницы на Сайте.</dd></dl><dl><dd>6.2. Разработчик вправе в любой момент приостанавливать или прекращать функционирование Приложения с уведомлением Пользователей или без такового.</dd></dl><dl><dd>6.3. Настоящие Правила регулируются и толкуются в соответствии с законодательством Украины. Вопросы, не урегулированные Правилами, подлежат разрешению в соответствии с законодательством Украины.</dd></dl><dl><dd>6.4. В случае возникновения любых споров или разногласий, связанных с исполнением настоящих Правил, Разработчик и Пользователь приложат все усилия для их разрешения путем проведения переговоров между ними. В случае, если споры не будут разрешены путем переговоров, споры подлежат разрешению в порядке, установленном действующим законодательством Украины.</dd></dl><dl><dd>6.5. Настоящие Правила составлены на русском языке и могут быть предоставлены Пользователю для ознакомления на другом языке. В случае расхождения русскоязычной версии Правил и версии Правил на ином языке, применяются положения русскоязычной версии настоящих Правил.</dd></dl><dl><dd>6.6. Если по тем или иным причинам одно или несколько положений настоящих Правил будут признаны недействительными или не имеющими юридической силы, это не оказывает влияния на действительность или применимость остальных положений.</dd></dl>',
					'Terms'=>'Правила',
					'Help'=>'Помощь',
					'Help text'=>'<ul class="faq">
						<li><a href="#start">Начало</a></li>
						<li><a href="#menu">Меню</a></li>
						<li><a href="#sections">Разделы</a></li>
						<li><a href="#projects">Проекты</a>
							<ul>
								<li><a href="#addproject">Добавить проект</a></li>
								<li><a href="#task">Пункты задания</a></li>
							</ul>
						</li>
						<li><a href="#finance">Финансы</a>
							<ul>
								<li><a href="#addtransaction">Добавить операцию</a></li>
								<li><a href="#financeanalytics">Финансовая аналитика</a></li>
							</ul>
						</ul>
						<p><a name="start"></a><h2>Начало</h2><img src="img/faq/home.png" width="700" /></p>
						<p><a name="menu"></a><h2>Меню</h2><img src="img/faq/menu.png" width="700" /></p>
						<p><a name="sections"></a><h2>Разделы</h2><img src="img/faq/section.png" width="700" /></p>
						<p><a name="projects"></a><h2>Проекты</h2><img src="img/faq/projects.png" width="700" /></p>
						<p><a name="projects"></a><h3>Добавить проект</h3><img src="img/faq/addproject.png" width="700" /></p>
						<p><a name="task"></a><h3>Пункты задания</h3><img src="img/faq/taskitems.png" width="700" /></p>
						<p><a name="finance"></a><h2>Финансы</h2><img src="img/faq/finance.png" width="700" /></p>
						<p><a name="addtransaction"></a><h3>Добавить операцию</h3><img src="img/faq/addtransaction.png" width="700" /></p>
						<p><a name="financeanalytics"></a><h3>Финансовая аналитика</h3><img src="img/faq/fanalytics.png" width="700" /></p>',
					'Add a task items'=>'Добавить пункты задания',
					'Settings'=>'Настройки',
					'Home'=>'Главная',
					'Projects'=>'Проекты',
					'Clients and contractors'=>'Клиенты и контрагенты',
					'Finance'=>'Финансы',
					'Makeup'=>'Верстка',
					'Users'=>'Пользователи',
					'Logout'=>'Выйти',
					'Archive'=>'Архив',
					'Sites'=>'Сайты',
					'Login'=>'Войти',
					'Public'=>'Опубликовано',
					''=>'',
					''=>'',
					''=>''
					);
				break;
			}
		}
		return $lng;
	}
}
?>