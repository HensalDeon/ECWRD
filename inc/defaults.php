<?php
/**
 * Default (seed) content.
 *
 * This is the single source of truth for the out-of-the-box copy. It is used:
 *  - by the importer, to populate the editable fields on first setup;
 *  - as `default_value` on the registered fields;
 *  - as a front-end fallback while content has not been saved yet, so the
 *    site never renders empty sections.
 *
 * Multi-line values (e.g. headings that reveal line by line) are stored as
 * newline-separated strings, exactly as they are edited in the admin.
 *
 * @package ECWRD
 */

defined( 'ABSPATH' ) || exit;

/**
 * Language-neutral site settings.
 *
 * @return array<string, string>
 */
function ecwrd_default_global_settings() {
	return array(
		'phone'     => '+971 50 987 6543',
		'email'     => 'info@ecwrd.ae',
		'linkedin'  => 'https://www.linkedin.com/company/ecwrd',
		'instagram' => 'https://www.instagram.com/ecwrd_ae/',
		'x'         => 'https://x.com/ECWRD',
	);
}

/**
 * Translatable content, keyed by language then by content area.
 *
 * @return array<string, array<string, mixed>>
 */
function ecwrd_default_content() {
	static $content = null;

	if ( null !== $content ) {
		return $content;
	}

	$content = array(
		'en' => array(
			'nav'        => array(
				'home'       => 'Home',
				'about'      => 'About Us',
				'leadership' => 'Leadership',
			),
			'settings'   => array(
				'council_name' => 'Emirates Council for Work Relation Development',
				'footer'       => array(
					'address_title'   => 'Address',
					'address'         => "Emirates Council for Work Relation Development\nAbu Dhabi National Exhibition Centre\nAl Rawdah — W59 01 — Abu Dhabi, United Arab Emirates",
					'inquiries_title' => 'General Inquiries',
					'follow_title'    => 'Follow',
					'language_label'  => 'Language',
					'copyright'       => '© {year} Emirates Council for Work Relation Development. All rights reserved.',
					'location'        => 'Abu Dhabi, United Arab Emirates',
				),
				'not_found'    => array(
					'overline'   => 'Error 404',
					'heading'    => "This page\ncould not be found.",
					'text'       => 'The page you are looking for may have moved or no longer exists.',
					'link_label' => 'Return Home',
				),
				'seo'          => array(
					'description' => 'Emirates Council for Work Relation Development (ECWRD) — an Abu Dhabi-based institutional platform dedicated to relationship development, creating trusted access and opening doors in the UAE.',
				),
			),
			'home'       => array(
				'hero'       => array(
					'overline' => 'Emirates Council for Work Relation Development',
					'lines'    => "Opening Doors\nThrough Trusted\nRelationships.",
					'tagline'  => 'An Abu Dhabi-based institutional platform dedicated to relationship development — creating trusted access and opening doors that are often difficult to reach.',
				),
				'council'    => array(
					'num'     => '01',
					'label'   => 'The Council',
					'heading' => 'An institutional bridge to the United Arab Emirates.',
					'body'    => 'Emirates Council is an Abu Dhabi-based institutional platform dedicated to relationship development. We connect governments, companies, and investors internationally with the right entities, leaders, and opportunities in the United Arab Emirates — creating trusted access and opening doors that are often difficult to reach.',
				),
				'principles' => array(
					'num'   => '02',
					'label' => 'Our Principles',
					'items' => array(
						array(
							'title' => 'Connect',
							'desc'  => 'Connecting governments, companies, and investors with the right relationships in the UAE.',
						),
						array(
							'title' => 'Access',
							'desc'  => 'Creating trusted access to decision-makers, institutions, and opportunities that can be difficult to reach.',
						),
						array(
							'title' => 'Advance',
							'desc'  => 'Helping the right relationships move forward through credible introductions and institutional trust.',
						),
					),
				),
				'approach'   => array(
					'num'             => '03',
					'label'           => 'Our Approach',
					'heading'         => 'Relationships. Access. Opportunity.',
					'body'            => 'We identify the right connection, bring the right parties together, and open doors through trusted relationships.',
					'link_about'      => 'About the Council',
					'link_leadership' => 'Our Leadership',
				),
				'seo'        => array(
					'title'       => 'Emirates Council for Work Relation Development | ECWRD',
					'description' => 'An Abu Dhabi-based institutional platform dedicated to relationship development — connecting governments, companies and investors with the right entities, leaders and opportunities in the UAE.',
				),
			),
			'about'      => array(
				'hero'           => array(
					'overline' => 'About Us',
					'lines'    => "An Institution\nin Service of\nthe Nation.",
					'since'    => 'Since 2009 — Abu Dhabi, United Arab Emirates',
					'intro'    => 'Emirates Council is an Abu Dhabi-based institutional platform dedicated to relationship development. We connect governments, companies, and investors internationally with the right entities, leaders, and opportunities in the United Arab Emirates — creating trusted access and opening doors that are often difficult to reach.',
				),
				'purpose'        => array(
					'num'     => '01',
					'label'   => 'Our Purpose',
					'heading' => 'Relationship development. Nothing else.',
					'body'    => 'ECWRD does one thing: it develops trusted relationships. We connect governments, companies and investors from around the world with the right government entities, companies, institutions, leaders and investment stakeholders in the UAE — helping the right parties meet, and opening doors that are difficult to open.',
				),
				'mission_vision' => array(
					'num'           => '02',
					'label'         => 'Mission & Vision',
					'mission_title' => 'Our Mission',
					'mission'       => 'To develop trusted relationships that connect international governments, companies, and investors with the right people and institutions in the United Arab Emirates.',
					'vision_title'  => 'Our Vision',
					'vision'        => "To be the UAE's trusted institutional bridge for relationships that create meaningful international partnerships and investment opportunities.",
				),
				'values'         => array(
					'num'   => '03',
					'label' => 'What We Stand For',
					'items' => array(
						array(
							'title' => 'National Responsibility',
							'desc'  => 'Our first responsibility is to the UAE. We aim to represent the country well and contribute to work that supports its people, institutions and long-term interests.',
						),
						array(
							'title' => 'Professional Integrity',
							'desc'  => 'We deal with every partner honestly and discreetly. We make commitments carefully and handle them with the seriousness they deserve.',
						),
						array(
							'title' => 'Regional & International Engagement',
							'desc'  => 'We approach our work across the GCC and international markets with cultural awareness, institutional understanding and careful consideration of the priorities involved.',
						),
						array(
							'title' => 'Commitment to the Work',
							'desc'  => 'We give proper attention to preparation, coordination and follow-up. What matters is that our part is completed well and that others can rely on us.',
						),
					),
				),
				'approach'       => array(
					'num'     => '04',
					'label'   => 'Our Approach',
					'heading' => 'Relationships. Access. Opportunity.',
					'body'    => 'We identify the right connection, bring the right parties together, and open doors through trusted relationships.',
				),
				'seo'            => array(
					'title'       => 'About Us | Emirates Council for Work Relation Development',
					'description' => 'Since 2009, the Emirates Council for Work Relation Development has connected governments, companies and investors with the right entities, leaders and opportunities in the UAE.',
				),
			),
			'leadership' => array(
				'hero'       => array(
					'overline' => 'Our Leadership',
					'lines'    => "Guided by Duty.\nLed with Purpose.",
					'intro'    => "The Council's leadership carries a shared conviction — that service to the nation is the highest form of work.",
				),
				'executives' => array(
					'label' => 'Executives',
				),
				'closing'    => array(
					'label' => 'Our Mission',
					'quote' => 'To develop trusted relationships that connect international governments, companies, and investors with the right people and institutions in the United Arab Emirates.',
				),
				'seo'        => array(
					'title'       => 'Leadership | Emirates Council for Work Relation Development',
					'description' => 'Meet the leadership of the Emirates Council for Work Relation Development — guided by duty and led with purpose in service of the nation.',
				),
			),
		),
		'ar' => array(
			'nav'        => array(
				'home'       => 'الرئيسية',
				'about'      => 'من نحن',
				'leadership' => 'قيادتنا',
			),
			'settings'   => array(
				'council_name' => 'مجلس الإمارات لتطوير علاقات العمل',
				'footer'       => array(
					'address_title'   => 'العنوان',
					'address'         => "مجلس الإمارات لتطوير علاقات العمل\nمركز أبوظبي الوطني للمعارض\nالروضة — W59 01 — أبوظبي، الإمارات العربية المتحدة",
					'inquiries_title' => 'للاستفسارات العامة',
					'follow_title'    => 'تابعونا',
					'language_label'  => 'اللغة',
					'copyright'       => '© {year} مجلس الإمارات لتطوير علاقات العمل. جميع الحقوق محفوظة.',
					'location'        => 'أبوظبي، الإمارات العربية المتحدة',
				),
				'not_found'    => array(
					'overline'   => 'خطأ 404',
					'heading'    => "تعذّر العثور\nعلى هذه الصفحة.",
					'text'       => 'ربما نُقلت الصفحة التي تبحث عنها أو لم تعد موجودة.',
					'link_label' => 'العودة إلى الرئيسية',
				),
				'seo'          => array(
					'description' => 'مجلس الإمارات لتطوير علاقات العمل — منصة مؤسسية مقرها أبوظبي تُعنى بتطوير العلاقات، توفّر وصولًا موثوقًا وتفتح الأبواب في دولة الإمارات.',
				),
			),
			'home'       => array(
				'hero'       => array(
					'overline' => 'مجلس الإمارات لتطوير علاقات العمل',
					'lines'    => "نفتح الأبواب\nمن خلال علاقاتٍ\nموثوقة.",
					'tagline'  => 'منصة مؤسسية مقرها أبوظبي تُعنى بتطوير العلاقات — توفّر وصولًا موثوقًا وتفتح أبوابًا يصعب الوصول إليها في كثير من الأحيان.',
				),
				'council'    => array(
					'num'     => '01',
					'label'   => 'المجلس',
					'heading' => 'جسرٌ مؤسسي إلى دولة الإمارات العربية المتحدة.',
					'body'    => 'مجلس الإمارات منصة مؤسسية مقرها أبوظبي تُعنى بتطوير العلاقات. نصل الحكومات والشركات والمستثمرين من مختلف أنحاء العالم بالجهات والقيادات والفرص المناسبة في دولة الإمارات العربية المتحدة — بما يوفّر وصولًا موثوقًا ويفتح أبوابًا يصعب الوصول إليها في كثير من الأحيان.',
				),
				'principles' => array(
					'num'   => '02',
					'label' => 'مبادئنا',
					'items' => array(
						array(
							'title' => 'تواصل',
							'desc'  => 'ربط الحكومات والشركات والمستثمرين بالعلاقات المناسبة في دولة الإمارات.',
						),
						array(
							'title' => 'وصول',
							'desc'  => 'توفير وصولٍ موثوق إلى صنّاع القرار والمؤسسات والفرص التي يصعب الوصول إليها.',
						),
						array(
							'title' => 'تقدّم',
							'desc'  => 'دفع العلاقات الصحيحة قدمًا من خلال تعارفٍ موثوق وثقةٍ مؤسسية.',
						),
					),
				),
				'approach'   => array(
					'num'             => '03',
					'label'           => 'نهجنا',
					'heading'         => 'العلاقات. الوصول. الفرص.',
					'body'            => 'نحدد الصلة الصحيحة، ونجمع الأطراف المناسبة، ونفتح الأبواب من خلال علاقاتٍ موثوقة.',
					'link_about'      => 'عن المجلس',
					'link_leadership' => 'قيادتنا',
				),
				'seo'        => array(
					'title'       => 'مجلس الإمارات لتطوير علاقات العمل',
					'description' => 'منصة مؤسسية مقرها أبوظبي تُعنى بتطوير العلاقات — نصل الحكومات والشركات والمستثمرين بالجهات والقيادات والفرص المناسبة في دولة الإمارات.',
				),
			),
			'about'      => array(
				'hero'           => array(
					'overline' => 'من نحن',
					'lines'    => "مؤسسة\nفي خدمة\nالوطن.",
					'since'    => 'منذ عام 2009 — أبوظبي، الإمارات العربية المتحدة',
					'intro'    => 'مجلس الإمارات منصة مؤسسية مقرها أبوظبي تُعنى بتطوير العلاقات. نصل الحكومات والشركات والمستثمرين من مختلف أنحاء العالم بالجهات والقيادات والفرص المناسبة في دولة الإمارات العربية المتحدة — بما يوفّر وصولًا موثوقًا ويفتح أبوابًا يصعب الوصول إليها في كثير من الأحيان.',
				),
				'purpose'        => array(
					'num'     => '01',
					'label'   => 'غايتنا',
					'heading' => 'تطوير العلاقات. لا شيء سواه.',
					'body'    => 'يقوم المجلس بأمرٍ واحد: تطوير العلاقات الموثوقة. نصل الحكومات والشركات والمستثمرين من مختلف أنحاء العالم بالجهات الحكومية والشركات والمؤسسات والقيادات وأصحاب المصلحة الاستثمارية المناسبين في دولة الإمارات — نساعد الأطراف الصحيحة على الالتقاء، ونفتح الأبواب التي يصعب فتحها.',
				),
				'mission_vision' => array(
					'num'           => '02',
					'label'         => 'الرسالة والرؤية',
					'mission_title' => 'رسالتنا',
					'mission'       => 'تطوير علاقاتٍ موثوقة تصل الحكومات والشركات والمستثمرين الدوليين بالأشخاص والمؤسسات المناسبة في دولة الإمارات العربية المتحدة.',
					'vision_title'  => 'رؤيتنا',
					'vision'        => 'أن نكون الجسر المؤسسي الموثوق في دولة الإمارات للعلاقات التي تصنع شراكاتٍ دولية وفرصًا استثمارية ذات معنى.',
				),
				'values'         => array(
					'num'   => '03',
					'label' => 'قيمنا',
					'items' => array(
						array(
							'title' => 'المسؤولية الوطنية',
							'desc'  => 'مسؤوليتنا الأولى تجاه دولة الإمارات. نسعى إلى تمثيل الدولة على أفضل وجه والإسهام في أعمالٍ تدعم شعبها ومؤسساتها ومصالحها بعيدة المدى.',
						),
						array(
							'title' => 'النزاهة المهنية',
							'desc'  => 'نتعامل مع كل شريك بصدقٍ وتحفّظ مهني. نلتزم بتعهداتنا بعناية ونتعامل معها بالجدية التي تستحقها.',
						),
						array(
							'title' => 'الانخراط الإقليمي والدولي',
							'desc'  => 'نمارس عملنا في دول مجلس التعاون والأسواق الدولية بوعيٍ ثقافي وفهمٍ مؤسسي ومراعاةٍ دقيقة للأولويات القائمة.',
						),
						array(
							'title' => 'الالتزام بالعمل',
							'desc'  => 'نولي الإعداد والتنسيق والمتابعة العناية اللازمة. ما يهمنا هو إتمام دورنا على أكمل وجه وأن يكون بمقدور الآخرين الاعتماد علينا.',
						),
					),
				),
				'approach'       => array(
					'num'     => '04',
					'label'   => 'نهجنا',
					'heading' => 'العلاقات. الوصول. الفرص.',
					'body'    => 'نحدد الصلة الصحيحة، ونجمع الأطراف المناسبة، ونفتح الأبواب من خلال علاقاتٍ موثوقة.',
				),
				'seo'            => array(
					'title'       => 'من نحن | مجلس الإمارات لتطوير علاقات العمل',
					'description' => 'منذ عام 2009، يصل مجلس الإمارات لتطوير علاقات العمل الحكومات والشركات والمستثمرين بالجهات والقيادات والفرص المناسبة في دولة الإمارات.',
				),
			),
			'leadership' => array(
				'hero'       => array(
					'overline' => 'قيادتنا',
					'lines'    => "يقودها الواجب.\nوتحدوها الغاية.",
					'intro'    => 'تحمل قيادة المجلس قناعةً مشتركة — بأن خدمة الوطن هي أسمى صور العمل.',
				),
				'executives' => array(
					'label' => 'الفريق التنفيذي',
				),
				'closing'    => array(
					'label' => 'رسالتنا',
					'quote' => 'تطوير علاقاتٍ موثوقة تصل الحكومات والشركات والمستثمرين الدوليين بالأشخاص والمؤسسات المناسبة في دولة الإمارات العربية المتحدة.',
				),
				'seo'        => array(
					'title'       => 'قيادتنا | مجلس الإمارات لتطوير علاقات العمل',
					'description' => 'تعرّف على قيادة مجلس الإمارات لتطوير علاقات العمل — يقودها الواجب وتحدوها الغاية في خدمة الوطن.',
				),
			),
		),
	);

	return $content;
}

/**
 * Default leadership roster. Images live in /assets/seed and are copied into
 * the Media Library by the importer.
 *
 * @return array<int, array<string, mixed>>
 */
function ecwrd_default_leaders() {
	return array(
		array(
			'key'     => 'founder',
			'section' => 'board',
			'image'   => 'founder.png',
			'en'      => array(
				'name'  => 'H.E. Dr. Abdullah Salem Al Humaid',
				'role'  => 'Founder',
				'quote' => 'Serving the country has always been the purpose behind this work. From the beginning, the aim was to bring people and institutions closer, encourage cooperation, and support efforts that benefit society and the nation.',
				'bio'   => "Dr. Abdullah Salem Al Humaid established the Emirates Council for Work Relation Development as a platform rooted in service, cooperation and national contribution. His work has long been connected to institutional relations and the strengthening of trusted ties across the UAE. As Founder, he represents the Council's original purpose and the values it continues to carry into its next stage.",
			),
			'ar'      => array(
				'name'  => 'معالي الدكتور عبدالله سالم الحميد',
				'role'  => 'المؤسس',
				'quote' => 'لطالما كانت خدمة الوطن هي الغاية من هذا العمل. فمنذ البداية، كان الهدف تقريب الناس والمؤسسات بعضهم من بعض، وتشجيع التعاون، ودعم الجهود التي تعود بالنفع على المجتمع والوطن.',
				'bio'   => 'أسّس الدكتور عبدالله سالم الحميد مجلس الإمارات لتطوير علاقات العمل منصةً راسخة في الخدمة والتعاون والإسهام الوطني. وقد ارتبط عمله طويلًا بالعلاقات المؤسسية وترسيخ أواصر الثقة في دولة الإمارات. وبصفته المؤسس، يمثّل الغاية الأصيلة للمجلس والقيم التي يواصل حملها نحو مرحلته المقبلة.',
			),
		),
		array(
			'key'     => 'honorary-chairman',
			'section' => 'board',
			'image'   => 'honorary-chairman.png',
			'en'      => array(
				'name'  => 'H.H. Sheikh Zayed bin Mohammed bin Khalifa Al Nahyan',
				'role'  => 'Honorary Chairman',
				'quote' => "The Council's role is to support work that serves the country and contributes to its development. When institutions work with sincerity, clear purpose and respect for national priorities, they can create opportunities that strengthen cooperation and support the UAE's wider vision for progress.",
				'bio'   => "His role reflects the Council's commitment to national service, unity and cross-sector cooperation. In his honorary position, he supports the Council's broader direction and its efforts to bring people, institutions and opportunities together in ways that contribute to the UAE's development.",
			),
			'ar'      => array(
				'name'  => 'سمو الشيخ زايد بن محمد بن خليفة آل نهيان',
				'role'  => 'الرئيس الفخري',
				'quote' => 'يتمثل دور المجلس في دعم العمل الذي يخدم الوطن ويسهم في تنميته. فحين تعمل المؤسسات بإخلاصٍ ووضوح غايةٍ واحترامٍ للأولويات الوطنية، يمكنها أن تصنع فرصًا تعزز التعاون وتدعم رؤية الإمارات الأشمل للتقدم.',
				'bio'   => 'يعكس دور سموه التزام المجلس بالخدمة الوطنية والوحدة والتعاون بين القطاعات. ويدعم سموه، من موقعه الفخري، التوجه العام للمجلس وجهوده في الجمع بين الأفراد والمؤسسات والفرص بما يسهم في تنمية دولة الإمارات.',
			),
		),
		array(
			'key'     => 'chairman',
			'section' => 'board',
			'image'   => 'chairman.png',
			'en'      => array(
				'name'  => 'Hazzaa Abdullah Al Humaid',
				'role'  => 'Chairman',
				'quote' => 'ECWRD is entering a new stage. We want the Council to be a place where the right people meet, where partnerships are taken seriously, and where ideas are turned into work that can actually be delivered.',
				'bio'   => "As Chairman, Hazzaa Abdullah Al Humaid leads the overall direction of the Council, ensuring its work reflects the UAE's priorities and long-term vision. He oversees the Council's strategic positioning, institutional relationships and future direction while guiding its continued growth through meaningful partnerships, responsible leadership and relationships that create lasting value.",
			),
			'ar'      => array(
				'name'  => 'هزاع عبدالله الحميد',
				'role'  => 'رئيس مجلس الإدارة',
				'quote' => 'يدخل المجلس مرحلةً جديدة. نريد له أن يكون مكانًا يلتقي فيه الأشخاص المناسبون، وتُؤخذ فيه الشراكات على محمل الجد، وتتحول فيه الأفكار إلى عملٍ قابلٍ للتنفيذ فعلًا.',
				'bio'   => 'يقود هزاع عبدالله الحميد، بصفته رئيسًا لمجلس الإدارة، التوجه العام للمجلس، بما يضمن انسجام عمله مع أولويات دولة الإمارات ورؤيتها بعيدة المدى. ويشرف على الموقع الاستراتيجي للمجلس وعلاقاته المؤسسية واتجاهه المستقبلي، موجّهًا نموه المتواصل عبر شراكاتٍ ذات معنى وقيادةٍ مسؤولة وعلاقاتٍ تصنع قيمةً دائمة.',
			),
		),
		array(
			'key'     => 'vice-chairman',
			'section' => 'board',
			'image'   => 'vice-chairman.png',
			'en'      => array(
				'name'  => 'Nasser Abdullah Al Humaid',
				'role'  => 'Vice Chairman',
				'quote' => '',
				'bio'   => "Nasser Abdullah Al Humaid brings experience across events, sport, entertainment and emerging sectors. As Vice Chairman, he helps bring cultural relevance and strategic relationships to the Council's direction — particularly where sport, entertainment, technology and youth engagement create new connections.",
			),
			'ar'      => array(
				'name'  => 'ناصر عبدالله الحميد',
				'role'  => 'نائب رئيس مجلس الإدارة',
				'quote' => '',
				'bio'   => 'يمتلك ناصر عبدالله الحميد خبرةً تمتد عبر الفعاليات والرياضة والترفيه والقطاعات الناشئة. وبصفته نائبًا لرئيس مجلس الإدارة، يسهم في إثراء توجه المجلس بالحضور الثقافي والعلاقات الاستراتيجية — لا سيما حيث تصنع الرياضة والترفيه والتقنية ومشاركة الشباب صلاتٍ جديدة.',
			),
		),
		array(
			'key'     => 'ceo',
			'section' => 'board',
			'image'   => 'ceo.png',
			'en'      => array(
				'name'  => 'Salem Abdullah Al Humaid',
				'role'  => 'Chief Executive Officer',
				'quote' => 'We are not here only to introduce people and leave the work unfinished. We want to understand what each side needs, connect the right partners, and help build the relationships that create opportunities for Emiratis and for serious international partners.',
				'bio'   => 'Salem Abdullah Al Humaid leads the day-to-day executive direction of ECWRD, with a focus on international relations and trusted introductions. He works to turn conversations into real openings by connecting international governments, companies and investors with the right institutions, stakeholders and partners across the UAE and the wider region.',
			),
			'ar'      => array(
				'name'  => 'سالم عبدالله الحميد',
				'role'  => 'الرئيس التنفيذي',
				'quote' => 'لسنا هنا لمجرد التعريف بين الأطراف وترك العمل ناقصًا. نريد أن نفهم احتياجات كل طرف، ونصل الشركاء المناسبين ببعضهم، ونساعد في بناء العلاقات التي تصنع الفرص للإماراتيين وللشركاء الدوليين الجادين.',
				'bio'   => 'يقود سالم عبدالله الحميد الإدارة التنفيذية اليومية للمجلس، مع تركيزٍ على العلاقات الدولية والتعارف الموثوق. ويعمل على تحويل الحوارات إلى أبوابٍ حقيقية من خلال ربط الحكومات والشركات والمستثمرين الدوليين بالمؤسسات والجهات والشركاء المناسبين في دولة الإمارات والمنطقة الأوسع.',
			),
		),
		array(
			'key'     => 'cso',
			'section' => 'executive',
			'image'   => 'cso.png',
			'en'      => array(
				'name'  => 'Falah Abdullah Al Humaid',
				'role'  => 'Chief Strategy Officer',
				'quote' => '',
				'bio'   => "Guides the Council's long-term direction — evaluating relationships and opportunities carefully, supporting long-term decision-making, and shaping priorities that align with ECWRD's purpose and the UAE's interests.",
			),
			'ar'      => array(
				'name'  => 'فلاح عبدالله الحميد',
				'role'  => 'الرئيس التنفيذي للاستراتيجية',
				'quote' => '',
				'bio'   => 'يوجّه المسار بعيد المدى للمجلس — يقيّم العلاقات والفرص بعناية، ويدعم القرارات بعيدة المدى، ويسهم في صياغة أولوياتٍ تنسجم مع غاية المجلس ومصالح دولة الإمارات.',
			),
		),
		array(
			'key'     => 'cgo',
			'section' => 'executive',
			'image'   => 'cgo.png',
			'en'      => array(
				'name'  => 'Ahmed Abdullah Al Humaid',
				'role'  => 'Chief Growth Officer',
				'quote' => '',
				'bio'   => "Expands ECWRD's reach across international markets and institutional networks — opening new channels with embassies, economic councils and global organisations, and building the relationships needed to enter those circles properly.",
			),
			'ar'      => array(
				'name'  => 'أحمد عبدالله الحميد',
				'role'  => 'الرئيس التنفيذي للنمو',
				'quote' => '',
				'bio'   => 'يعمل على توسيع حضور المجلس في الأسواق الدولية والشبكات المؤسسية — يفتح قنواتٍ جديدة مع السفارات والمجالس الاقتصادية والمنظمات العالمية، ويبني العلاقات اللازمة لدخول تلك الأوساط على النحو الصحيح.',
			),
		),
		array(
			'key'     => 'cpo',
			'section' => 'executive',
			'image'   => 'cpo.jpg',
			'en'      => array(
				'name'  => 'Eissa Cavalcanti',
				'role'  => 'Chief Partnership Officer',
				'quote' => '',
				'bio'   => "Leads ECWRD's relationship development with stakeholders — working with government entities, private organisations and international partners to build strong ties, identify areas of cooperation, and turn the right meetings into lasting partnerships.",
			),
			'ar'      => array(
				'name'  => 'عيسى كافالكانتي',
				'role'  => 'الرئيس التنفيذي للشراكات',
				'quote' => '',
				'bio'   => 'يقود تطوير علاقات المجلس مع أصحاب المصلحة — يعمل مع الجهات الحكومية والمؤسسات الخاصة والشركاء الدوليين لبناء أواصر متينة وتحديد مجالات التعاون وتحويل اللقاءات الصحيحة إلى شراكاتٍ دائمة.',
			),
		),
	);
}
