/** CSS **/

/*
 * CSS php dynamic
 * DO NOT TIDY!
 * @uses color variables $primary_0 - $primary_6, $secondary_0 - $secondary_1
 */

/** GLOBAL RESETS **/
*, *::before, *::after { box-sizing: border-box; }

h1,h2,h3,h4,h5,h6,pre,code {
	font-size: 1em;
	font-weight: 400;
	word-wrap: break-word;
}

ul,ol,li,h1,h2,h3,h4,h5,h6,pre,form,body,html,p,blockquote,fieldset,input {
	margin: 0;
	padding: 0;
}

html {
	overflow-y: scroll;
	scroll-behavior: smooth;
	height: 100%;
}
/** End GLOBAL RESETS **/

:root {
	--gap: 20px;
	--clr-primary-darkest: <?php echo $primary_0; ?>;
	--clr-primary-darker: <?php echo $primary_1; ?>;
	--clr-primary-dark: <?php echo $primary_2; ?>;
	--clr-primary-middle: <?php echo $primary_3; ?>;
	--clr-primary-light: <?php echo $primary_4; ?>;
	--clr-primary-lighter: <?php echo $primary_5; ?>;
	--clr-primary-lightest: <?php echo $primary_6; ?>;
	--clr-secondary-darkest: <?php echo $secondary_0; ?>;
	--clr-secondary-lightest: <?php echo $secondary_1; ?>;
	--clr-neutral-darkest: #000;
	--clr-neutral-lightest: #fff;
	--bg-body: #f6f6f6;
	--bg-header: linear-gradient(to bottom, var(--clr-primary-light), var(--clr-primary-dark));
	--bg-nav-tabs: linear-gradient(to bottom, #fff 3%, #f6f6f6 100%);
	--bg-notification: linear-gradient(to bottom, #fffbcc, #fffbc1);
	--bg-notification-error: linear-gradient(to bottom, #fcf0ef, #f9dfdd);
	--bg-notification-ok: linear-gradient(to bottom, #f0ffed, #dfffd7);
	--bg-notification-info: linear-gradient(to bottom, #edf4ff, #d7e7ff);
	--bg-notification-warning: linear-gradient(to bottom, #fffed5, #fffdbf);
	--bg-btn: linear-gradient(to bottom, #eee, #ddd 30%);
	--bg-btn-hover: linear-gradient(to top, #eee, #ddd 70%);
}

body {
	height: 100%;
	font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
	font-size: 12px;
	background: var(--bg-body);
	color: black;
	text-rendering: optimizeLegibility;
}
body.lighter {
	--bg-header: linear-gradient(to bottom, var(--clr-primary-lighter), var(--clr-primary-middle));
}
body.middle {
	--bg-header: linear-gradient(to bottom, var(--clr-primary-middle), var(--clr-primary-darker));
}
body.dark {
	--bg-header: linear-gradient(to bottom, var(--clr-primary-dark), var(--clr-primary-darkest));
}
body.darker {
	--bg-header: linear-gradient(to bottom, var(--clr-primary-darker), var(--clr-primary-darkest));
}
body.flat {
	--bg-header: var(--clr-primary-middle);
	--bg-nav-tabs: #f6f6f6;
	--bg-notification: #fffbcc;
	--bg-notification-error: #fcf0ef;
	--bg-notification-ok: #f0ffed;
	--bg-notification-info: #edf4ff;
	--bg-notification-warning: #fffed5;
	--bg-btn: #ddd;
	--bg-btn-hover: #eee;
}
body.flat.light {
	--bg-header: var(--clr-primary-light);
}
body.flat.middle {
	--bg-header: var(--clr-primary-middle);
}
body.flat.dark {
	--bg-header: var(--clr-primary-dark);
}
body.flat.darker {
	--bg-header: var(--clr-primary-darker);
}
body.flat.darkest {
	--bg-header: var(--clr-primary-darkest);
}


/** FORMS DEFAULTS **/
form input.capslock {
	background-image: url('images/capslock.png');
	background-position: right center;
	background-repeat: no-repeat;
}

[type=text], [type=email], [type=password], [type=search], [type=file], [type=url], textarea, select {
	width: 100%;
}
:is([type=text], [type=email], [type=password], [type=search], [type=file], [type=url], textarea, select):focus {
	outline: none;
	border: 1px solid #666 !important;
	box-shadow: rgba(0,0,0, 0.10) 0px 0px 6px;
}
[type=text], [type=email], [type=password], [type=search], [type=url], textarea, select {
	color: #333;
	font-size: 12px;
	border: 1px solid #aaa;
	padding: 5px;
	border-radius: 2px;
}
[type=text], [type=email], [type=password], [type=search], [type=url], select {
	height: 26px;
}
textarea {
	height: 420px;
	line-height: 18px;
	/* resize: none; */
	resize: vertical;
	tab-size:4;
}
:is(input, select, textarea):disabled {
	color: #808080;
	background: #eeeeee;
}
:is(input, select, textarea)[readonly] {
	background: #eeeeee;
	border: 1px solid #999;
	color: #666;
	cursor: default;
}
::placeholder {
	color: #999;
}

/* hide input placeholders on focus */
input:focus::placeholder {
	color: transparent;
}
/* form submit button style */
input[type="submit"], input[type="reset"], input[type="button"], button, .button {
	font-size: 13px;
	font-weight: bold;
	text-decoration: none;
	padding: 7px 15px;
	text-shadow: 0 1px 0 rgba(255,255,255,.5);
	transition: all .218s;
	color: #333;
	background: var(--bg-btn);
	border: solid 1px #acacac;
	border-radius: 2px;
	cursor: pointer;
	box-shadow: rgba(0,0,0, 0.06) 0px 0px 3px;
}

:is(input[type="submit"], input[type="reset"], input[type="button"], button, .button):focus,
:is(input[type="submit"], input[type="reset"], input[type="button"], button, .button):hover {
	color: #111;
	background: var(--bg-btn-hover);
	border: solid 1px #aaa;
	box-shadow: rgba(0,0,0, 0.15) 0px 0px 4px;
}

:is(input[type="submit"], input[type="submit"]:hover, input[type="submit"]:focus).warning {
	border-color: var(--clr-secondary-lightest);
}
/** End FORMS DEFAULTS **/
#submit_line .button {
	font-weight: bold;
	color: #333;
}
#submit_line .button:focus, #submit_line .button:hover {
	color: #111;
}
/* .largeforms styles. */
.largeform {
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
	gap: var(--gap);
}
.largeform .leftsec, .largeform .rightsec {
	flex: 0 0 calc(50% - var(--gap) / 2);
	margin-bottom: 0;
}
.largeform .leftsec {
	margin-left: 0;
	margin-right: auto;
}
.largeform .rightsec {
	margin-left: auto;
	margin-rignt: 0;
}
.largeform .widesec, .largeform > *:not(:where(.leftsec, .rightsec, .backuplink)) {
	flex: 0 0 100%;
	margin-bottom: 0;
}
.largeform .backuplink {
	flex: 0 0 calc(100% + 2 * var(--gap));
	margin: 0 calc(-1 * var(--gap)) calc(-1 * var(--gap)) calc(-1 * var(--gap));
}
/* End .largeforms styles */
a img,
:link img,
:visited img {
	border: none;
}

.clear {
	clear: both;
}

.unformatted {
	white-space: pre;
	font-family: Consolas, "Andale Mono WT", "Andale Mono", "Lucida Console", "Lucida Sans Typewriter", "DejaVu Sans Mono", "Bitstream Vera Sans Mono", "Liberation Mono", "Nimbus Mono L", Monaco, "Courier New", Courier, monospace;
	line-height: 15px;
	font-size: 11px;
	display: block;
	padding-bottom: 15px;
	color: #555;
	overflow: auto;
}

#help {
	display: none;
}

.imgthumb {
	display: none;
	width: 70px;
}

.imgthumb img {
	border: 1px solid #555;
}

.hidden, .toggle {
	display: none;
}

.clearfix:before, .clearfix:after {
	content: "\0020";
	display: block;
	height: 0;
	visibility: hidden;
}

.clearfix:after {
	clear: both;
}

::selection {
	text-shadow: none !important;
	background: #a8d1ff;
	color: #111;
}

/** HEADER / NAVIGATION **/
#header {
	color: var(--clr-neutral-lightest);
	border-top: 1px solid var(--clr-primary-darker);
	background: var(--bg-header);
	margin: 0 0 calc(var(--gap) + 5px) 0;
}

#header .wrapper {
	position: relative;
	display: flex;
	flex-direction: column;
	padding-top: 0px;
}

#header a {
	text-decoration: none;
	font-weight: normal;
}

#header h1 {
	font-size: 24px;
	text-shadow: 1px 1px 0px var(--clr-primary-dark);
	margin: 18px 0;
}

#header h1 a:link,
#header h1 a:visited {
	color: var(--clr-primary-lighter);
}

#header h1 a:hover {
	color: var(--clr-neutral-lightest);
}

#header ul {
	list-style: none;
	display: flex;
}

#header .nav {
	font-size: 13px;
	flex-direction: row;
	align-items: flex-start;
}
#header .nav li {
	position: relative;
}
#header .nav li:not(:last-child) {
	margin-right: 8px;
}

#header .nav a {
	padding: 7px 13px;
	display: block;
	border-radius: 5px 5px 0 0;
}
#header .nav a:link,
#header .nav a:visited,
#pill a:link,
#pill a:visited {
	color: var(--clr-primary-lightest);
	background: var(--clr-primary-darker);
	text-shadow: 1px 1px 0px rgba(0,0,0,.3);
}

#header #nav_support {
	margin-left: auto;
	margin-right: 1px;
}

#header #nav_support a {
	border-radius: 3px 0 0 3px;
}
#header #nav_settings a {
	border-radius: 0 3px 3px 0;
}

#header #nav_support a, #header #nav_settings a {
	font-size: 11px;
	padding: 4px 10px;
}

#edit .wrapper .nav li a.pages,
#pages .wrapper .nav li a.pages,
#menu-manager .wrapper .nav li a.pages,
#plugins .wrapper .nav li a.plugins,
#settings .wrapper .nav li a.settings,
#components .wrapper .nav li a.components,
#theme .wrapper .nav li a.theme,
#sitemap .wrapper .nav li a.theme,
#theme-edit .wrapper .nav li a.theme,
#navigation .wrapper .nav li a.theme,
#upload .wrapper .nav li a.files,
#image .wrapper .nav li a.files,
#backups .wrapper .nav li a.backups,
#support .wrapper .nav li a.support,
#log .wrapper .nav li a.support,
#health-check .wrapper .nav li a.support,
#backup-edit .wrapper .nav li a.backups,
#archive .wrapper .nav li a.backups,
#load .wrapper .pages li a.pages,
#load .wrapper .plugins li a.plugins,
#load .wrapper .settings li a.settings,
#load .wrapper .theme li a.theme,
#load .wrapper .files li a.files,
#load .wrapper .backups li a.backups,
#load .wrapper .support li a.support,
#load .wrapper .nav li a.current,
#loadtab .wrapper .nav li a.current {
	color: var(--clr-primary-darker);
	background: var(--bg-nav-tabs);
	font-weight: bold !important;
	text-shadow: 1px 1px 0px rgba(255,244,255,.2);
	box-shadow: rgba(0,0,0, 0.10) 2px -2px 2px;
}

#header .nav a:active,
#header .nav a:focus,
#header .nav a:hover,
#pill a:hover,
#pill a:focus {
	color: #FFF;
	background: var(--clr-primary-darkest);
	text-shadow: 1px 1px 0px rgba(0,0,0,.4);
}

#header a em {
	font-style: normal;
}

#header .nav a:hover em, #header .nav a:focus em {
	border-bottom: 1px dotted currentColor;
}

#pill {
	font-size: 11px;
	flex-direction: row;
	justify-content: flex-end;
}

#pill a {
	padding: 4px 10px;
	font-weight: normal;
	text-decoration: none;
	display: block;
}

#pill .mode {
	text-transform: uppercase;
	font-weight: bold;
}

#pill .mode a:link,
#pill .mode a:visited,
#pill .mode a:hover {
	color: #fff;
	background: var(--clr-secondary-lightest);
}

#pill li:not(:first-child) {
	margin-left: 1px;
}

#pill li:first-child a {
	border-radius: 0 0 0 3px;
}

#pill li:last-child a {
	border-radius: 0 0 3px 0;
}

.wrapper {
	margin-left: auto;
	margin-right: auto;
	width: 960px;
	text-align: left;
}

.wrapper p {
	line-height: 18px;
	margin: 0 0 var(--gap) 0;
}

.wrapper #maincontent ul,
.wrapper #maincontent ol {
	line-height: 18px;
	margin: 0 0 var(--gap) calc(1.5 * var(--gap));
}

.wrapper a:link,
.wrapper a:visited {
	color: var(--clr-primary-middle);
	font-weight: bold;
}

.wrapper a:hover {
	color: #333;
	font-weight: bold;
}

.inner {
	padding: var(--gap);
}

.wrapper h2 {
	font-size: 18px;
	color: #777;
	margin: 0 0 var(--gap) 0;
}

.wrapper h2 span {
	color: #bbb;
	font-style: italic;
}

.attention, .is-private {
	color: var(--clr-secondary-lightest);
}

.disabled, .disabled label {
	color: #999;
}

h3 {
	font-size: 18px;
	font-weight: normal;
	color: var(--clr-secondary-lightest);
	margin: 0 0 var(--gap) 0;
	text-shadow: 1px 1px 0 #fff;
}

h5,
div.h5 {
	margin: 10px 0 10px 0;
	font-size: 14px;
	line-height: 28px;
	display: block;
	padding: 3px 10px;
	background: linear-gradient(to bottom, #f6f6f6 3%, #EEEEEE 100%);
	border: 1px solid #cccccc;
	text-shadow: 1px 1px 0 rgba(255,255,255,0.5);
	color: #999;
}

.flat h5, .flat .h5 {
	background: #EEEEEE;
}

h5 a {
	text-decoration: none !important;
}

h5 img,
tr.folder img {
	vertical-align: middle;
	margin: 0 5px 0 0;
	opacity: .5;
}

h5:hover img {
	opacity: 1;
}

.bodycontent {
	display: flex;
	flex-flow: row nowrap;
	justify-content: space-between;
	align-items: flex-start;
	column-gap: var(--gap);
}

.bodycontent ul,
.bodycontent ol {
	margin: 0 0 var(--gap) calc(1.5 * --gap);
}

.bodycontent ul p,
.bodycontent ol p {
	margin: 0 0 10px 0;
}

#maincontent {
	width: 710px;
}

#sidebar {
	width: 230px;
	position: sticky;
	top: var(--gap);
}

#sidebar .section {
	background: #fff;
	border: 1px solid #ccc;
	padding: var(--gap);
	margin: 0 0 30px 0;
	line-height: 18px;
}

#sidebar .section p.small {
	font-size: 11px;
	margin: 15px 0 0 0;
}

#sidebar .section input.text {
	width: 175px;
	font-size: 11px;
	padding: 4px;
	border: 1px solid #666;
}

#sidebar .snav {
	list-style: none;
	margin: 0 0 30px 0;
}

#sidebar .snav ul {
	list-style: none;
	margin: 0;
}

#sidebar .snav li {
	margin: 0 0 3px 0;
}

#sidebar .snav li ul li {
	margin: 0 0 3px 0;
}

#submit_line {
	margin: 15px 0;
}

#sidebar #js_submit_line {
	margin: 0 0 0 12px;
}

#sidebar .snav li a {
	font-weight: bold;
	display: block;
	padding: 5px 15px 5px 15px;
	text-decoration: none;
	border-radius: 3px;
}

#sidebar .snav li a:link,
#sidebar .snav li a:visited {
	margin-left: 13px;
	color: var(--clr-primary-lightest);
	background: var(--clr-primary-darker);
	text-shadow: 1px 1px 0px var(--clr-primary-darkest);
	transition: all .2s ease-in-out;
}

#sidebar .snav li a.current {
	margin-left: 0px;
	cursor: default;
	color: #FFF;
	background: var(--clr-secondary-lightest); /* url('images/active.png') center left no-repeat !important; */
	text-shadow: 1px 1px 0px var(--clr-secondary-darkest);
	padding-left: 28px;
	border-radius: 0 3px 3px 0;
	clip-path: polygon(100% 0%, 100% 100%, 14px 100%, 0% 50%, 14px 0%);
}

#sidebar .snav li a.current:hover {
	text-shadow: 1px 1px 0px var(--clr-secondary-darkest);
	margin-left: 0px;
	cursor: default;
	color: #FFF;
	background: var(--clr-secondary-lightest); /* url('images/active.png') center left no-repeat !important; */
	padding-left: 28px;
}

#sidebar .snav li a:hover {
	color: #FFF;
	background: var(--clr-primary-darkest);
	margin-left: 13px;
	text-shadow: 1px 1px 0px rgba(0,0,0,.25);
}

#sidebar .snav li a em {
	font-style: normal;
}

#sidebar .snav li a:hover em,
#sidebar .snav li a:focus em {
	border-bottom: 1px dotted #666;
}

#sidebar .snav li a.current:hover em,
#sidebar .snav li a.current:focus em  {
	border-bottom: 1px dotted #fff;
}

#sidebar .snav small {
	color: #666;
}

#sidebar #sb_filesize {
	width: fit-content;
	margin-left: auto;
}

.edit-nav {
	position: absolute;
	top: var(--gap);
	right: var(--gap);
	display: flex;
	flex-direction: row-reverse;
}

.edit-nav a {
	font-size: 10px;
	text-transform: uppercase;
	padding: 3px 10px;
	margin: 0 0 0 5px;
	border-radius: 3px;
}

.edit-nav select {
	width: auto;
}

.edit-nav p {
	font-size: 11px;
	margin: 0;
}

.edit-nav label {
	font-weight: normal;
	font-size: 11px;
	color: #666;
	margin: 0;
	padding: 0;
}

.edit-nav a#metadata_toggle::after {
	display: inline-block;
	width: 1ch;
	margin-left: 1ch;
	content: '+';
}

.edit-nav a#metadata_toggle.current::after {
	content: '-';
}

.edit-nav a:link,
.edit-nav a:visited {
	line-height: 14px !important;
	background-color: var(--clr-primary-darker);
	color: #ccc;
	font-weight: bold;
	text-decoration: none;
	text-shadow: 1px 1px 0px rgba(0,0,0,.2);
	transition: all .10s ease-in-out;
}

.edit-nav a:hover,
#sidebar .edit-nav a:hover,
.edit-nav a.current {
	background-color: var(--clr-secondary-lightest);
	color: #FFF;
	font-weight: bold;
	text-decoration: none;
	line-height: 14px !important;
	text-shadow: 1px 1px 0px rgba(0,0,0,.2);
}

.edit-nav a:link em,
.edit-nav a:visited em {
	font-style: normal;
}

.edit-nav a.current em,
.edit-nav a:hover em,
.edit-nav a:focus em {
	font-style: normal;
	border-bottom: 1px dotted #FFF;
}

/* basic default table style */
.wrapper table {
	border-collapse: collapse;
	margin: 0 0 20px 0;
	width: 100%;
}

.wrapper table td {
	vertical-align: top;
	padding: 4px;
	border-bottom: 1px solid #eee;
	border-top: 1px solid #eee;
	line-height: 20px !important;
	box-sizing: content-box;
}

.wrapper table th {
	background: #FFF !important;
	padding: 2px 4px;
	font-size: 11px;
	border-top: 1px solid #FFF;
	color: #222;
	font-weight: bold;
	text-transform: uppercase;
	line-height: 20px !important;
	text-align: left;
}

.wrapper table tr.head {
}

.wrapper table td span {
	font-size: 12px;
	color: #777;
}

.wrapper table.highlight {
	text-shadow: 1px 1px 0 #fff;
}

.wrapper table.highlight tr:nth-child(odd) {
	 background: #f7f7f7;
}

.wrapper table tr#tr-index a {
	 font-weight: bold !important;
}

.wrapper table.highlight tr:hover {
	background: #FFFFD5 !important;
	text-shadow: none;
}

.wrapper table tr.currentpage{
	 background: #FFFFD1;
}

.wrapper table tr {
	border-bottom: 1px solid #eee;
	border-top: 1px solid #eee;
	transition: background-color .1s ease-in-out;
}

table td a {
	font-weight: normal !important;
}

.wrapper table.healthcheck tr td {
	font-size: 12px;
}

.popup table td {
	padding: 4px;
}

.popup table a:link,
.popup table a:visited {
	color: var(--clr-primary-middle);
	text-decoration: underline;
}

.popup table a:hover {
	color: #333;
	text-decoration: underline;
}

sup {
	color: #666;
	font-weight: normal !important;
	vertical-align: baseline;
	font-size: 0.8em;
	position: relative;
	top: -0.4em;
}

/* default form css */
#maincontent .main {
	padding: var(--gap);
	background: #fff;
	border: 1px solid #c8c8c8;
	box-shadow: rgba(0,0,0, 0.06) 0px 0px 4px;
	position: relative;
}

#maincontent .main .section {
	padding-top: calc(var(--gap) * 2);
}

form p {
	margin: 0 0 10px 0;
}

textarea#codetext {
	height: 660px;
	font-family: Consolas, Monaco, "Courier New", Courier, monospace;
	font-size: 12px;
	line-height: 18px;
	overflow: scroll;
	overflow-y: scroll;
	overflow-x: scroll;
}

label {
	padding: 0;
	margin: 0;
	color: #222;
	display: block;
	font-size: 12px;
	font-weight: bold;
}

label span.right {
	font-size: 11px;
	float: right;
	color: #999;
	display: inline-block;
	font-weight: normal;
}

label span.right a:link,
label span.right a:visited {
	color: #999;
	text-decoration: none;
	font-weight: normal;
}

label span.right a:focus,
label span.right a:hover {
	color: #333;
	text-decoration: underline;
	font-weight: normal;
}

.inline label {
	display: inline-block;
}

.hint {
	color: #999;
}

/* meta dropdown style */
#metadata_window {
	margin-bottom: var(--gap);
	background: #f9f9f9;
	border: 1px solid #e8e8e8;
	padding: calc(var(--gap) / 2);
	border-radius: 2px;
	text-shadow: 1px 1px 0 rgba(255,255,255,.3);
}

#metadata_window .rightopt {
	float: right;
	width: 50%;
}

#metadata_window .leftopt {
	float: left;
	width: 50%;
}

#metadata_window .wideopt {
	clear:both;
	width: 100%;
}

#metadata_window p {
	margin: 0 0 15px 0;
}

#metadata_window input,
#metadata_window select,
#metadata_window textarea {
	font-size: 11px;
	margin: 0 !important;
}

#metadata_window input[type="checkbox"] {
	width: 20px;
	padding: 0;
	margin: 0;
}

#metadata_window textarea {
	height: 62px;
	margin-bottom: 0;
	line-height: 15px;
}

#metadata_window p.post-menu {
	margin-bottom: 5px;
}

#metadata_window a.viewlink img {
	vertical-align: baseline;
	margin-left: 15px;
	opacity: .5;
}

#metadata_window a.viewlink:hover img {
	opacity: 1;
}

#metadata_window #menu-items {
	height: 60px;
	display: flex;
	justify-content: space-between;
	column-gap: 10px;
	text-transform: lowercase;
	background: #222;
	clip-path: polygon(15px 5px, 100% 5px, 100% 100%, 0px 100%, 0px 5px, 5px 5px, 10px 0px);
	padding: 10px;
	margin-top: -5px;
}

#metadata_window #menu-items div:first-child {
	flex: 1;
}
#metadata_window #menu-items div:last-child {
	width: 20%;
}

#metadata_window #menu-items input,
#metadata_window #menu-items select {
	border: 1px solid #000;
	border-radius: 0;
	padding: 3px;
	height: 20px;
}

#metadata_window #menu-items label {
	text-shadow: none;
	font-size: 11px;
	line-height: 16px;
	color: #e3e3e3;
	font-weight: normal;
	margin: 0;
	padding: 0;
}

#metadata_window label span.right {
	display: inline-block;
	color: #999;
	font-size: 11px;
	font-weight: normal;
	float: right;
	margin-right: 0;
	text-transform: lowercase;
}

#autosavenotify {
	padding: 15px 0 0 13px;
	color: #666;
	text-shadow: 1px 1px 0 #fff;
	font-size: 11px;
}

#pagechangednotify {
	margin: 0px;
	padding: 15px 0 0 13px;
	color: var(--clr-secondary-lightest);
	font-size: 11px;
}

p.backuplink {
	text-shadow: 1px 1px 0 rgba(255,255,255,.3);
	color: #888;
	font-size: 11px;
	margin: 20px -20px -20px -20px;
	padding: 10px 8px 10px 40px;
	background: #f9f9f9 url('images/clock.png') 20px center no-repeat;
	border-top: 1px solid #eee;
}

p.backuplink a {
	font-weight: normal;
}

.editing {
	font-size: 10px;
	padding: 3px;
	display: block;
	margin-top: -13px;
	margin-bottom: -10px;
	color: #888;
	font-style: italic;
}

:is(.leftsec, .rightsec, .widesec) textarea {
	height: 62px;
}

/* edit css */
#post-title {
	font-size: 18px;
	border-color: #999;
	height: 30px;
}

form input.secondary {
	width: 280px;
}

/* components css */
form.manyinputs input.text {
	width: 230px;
}

form.manyinputs textarea {
	height: 200px;
}

form.manyinputs p:not(.backuplink) {
	margin: 0 0 25px 0;
}

.compdiv {
	padding-bottom: 10px;
}

table.comptable {
	margin: 0px !important;
	width: 100%;
	background: #fff;
	border: none;
	padding: 0;
}

table.comptable td {
	font-size: 12px;
	border: none;
	vertical-align: middle;
}

table.comptable td:first-child {
	padding-left: 0;
	width: 1ch;
}

table.comptable td:last-child {
	padding-right: 0;
}

table.comptable tr td code {
	font-size: 11px;
	color: #666;
	padding: 0 4px 0 0;
	display: block;
	font-family: Consolas, Monaco, "Courier New", Courier, monospace;
}

table.comptable .compslugcode {
	font-size: 11px;
}

table.comptable tr {
	border: none !important;
}

table.comptable input[name$="[enabled]"] {
	vertical-align: middle;
}

table.comptable input[name$="[enabled]"] + label {
	display: none;
}

.wrapper a.component {
	float: left;
	font-weight: bold;
	margin: 0 5px 5px 0;
	padding: 3px 10px;
	text-decoration: none;
	border-radius: 3px;
}

.wrapper a.component:link, .wrapper a.component:visited {
	color: #666;
	background: #fff;
	border: 1px solid #999;
	text-decoration: none;
	overflow-wrap: anywhere;
}

.wrapper a.component:hover {
	color: var(--clr-primary-lightest);
	background: var(--clr-primary-darker);
	border: 1px solid var(--clr-primary-darkest);
	text-decoration: none;
}

.compdivlist {
	padding: 30px 0;
	text-align: center;
	margin: 0 0 0 15px;
	overflow: auto;
}

/* Notification styles
 *
 * alerts are now notifications
 * use classes notify and notification
 *
 */

.notify, .notification {
	border: 1px solid #E6DB55;
	border-radius: 2px;
	background: var(--bg-notification);
	line-height: 22px;
	padding: 5px 10px;
	margin-bottom: var(--gap);
	display: flex;
	align-items: flex-start;
	justify-content: space-between;
	column-gap: calc(var(--gap) / 2);
}

.notify p, .notification p {
	margin: 0;
	line-height: 22px;
}

.notify button.close, .notification button.close {
	border: none;
	background: none;
	color: inherit;
	cursor: pointer;
	font-size: 16px;
	padding: 1px;
	text-shadow: none;
	box-shadow: none;
}

.notify.error, .notification.error {
	color: #990000;
	border-color: #990000;
	background: var(--bg-notification-error);
}

.notify.ok, .notification.ok {
	border-color: #258815;
	color: #258815;
	background: var(--bg-notification-ok);
}

.notify.info, .notification.info {
	border-color: #2850C1;
	color: #2850C1;
	background: var(--bg-notification-info);
}

.notify.warning, .notification.warning {
	border-color: #878314;
	color: #878314;
	background: var(--bg-notification-warning);
}

.deletedrow {
	background-color: #FFB19B;
}

.notify code, .notification code {
	color: #990000;
	font-size: 11px;
	font-family: Consolas, "Andale Mono WT", "Andale Mono", "Lucida Console", "Lucida Sans Typewriter", "DejaVu Sans Mono", "Bitstream Vera Sans Mono", "Liberation Mono", "Nimbus Mono L", Monaco, "Courier New", Courier, monospace;
	line-height: 14px;
	background: #fff;
	opacity: .8;
	padding: 1px;
}

.notify.updated, .notification.updated, #temp_good_flash {
	color: #308000;
}

.hint {
	color: #777;
	-ms-word-break: break-all;
	    word-break: break-all;
	    word-break: break-word;
	-webkit-hyphens: auto;
	   -moz-hyphens: auto;
	        hyphens: auto;
}

/* file listing table style */
#filetypetoggle {
	color: #999;
	font-size: 12px;
}

table td.delete {
	width: 20px;
	text-align: center;
}

table td.delete a {
	font-size: 18px !important;
	line-height: 16px;
}

.view {
	width: 48px;
	text-align: center;
}

.editl {
	width: 40px;
	text-align: center;
}

.editlw {
	width: 220px;
	text-align: left;
}

.delete a:link,
.delete a:visited {
	color: #999 !important;
	text-decoration: none !important;
	padding: 1px;
	display: block;
	line-height: 16px;
	font-size: 12px;
	font-weight: normal;
	transition: all .02s ease-in-out;
	font-style:normal;
}

.delete a:hover {
	background: var(--clr-secondary-lightest) !important;
	color: #fff !important;
	text-decoration: none !important;
	padding: 1px;
	line-height: 16px;
	display: block;
	font-size: 12px;
	font-weight: normal;
}

a.cancel:link,
a.cancel:visited {
	font-weight: normal;
	color: var(--clr-secondary-lightest) !important;
	text-decoration: underline;
	padding: 1px 3px;
	background: none !important;
	line-height: 16px;
	transition: all .02s ease-in-out;
}

a.cancel:hover {
	font-weight: normal;
	background: var(--clr-secondary-lightest) !important;
	color: #fff !important;
	text-decoration: none !important;
	padding: 1px 3px;
	line-height: 16px;
}

a.cancel em {
	font-style: normal;
}

.wrapper .secondarylink {
	width: 20px;
	text-align: center;
	font-size: 12px;
	line-height: 14px;
}

.wrapper .secondarylink a:link,
.wrapper .secondarylink a:visited {
	color: #aaa;
	text-decoration: none;
	font-weight: normal;
	padding: 1px;
	display: block;
	line-height: 16px;
	transition: all .15s ease-in-out;
}

.wrapper .secondarylink a:hover {
	background: var(--clr-primary-middle);
	color: #FFF;
	text-decoration: none;
	font-weight: normal;
	padding: 1px;
	display: block;
	line-height: 16px;
}

.delete a:link, .delete a:visited, .wrapper .secondarylink a:link, .wrapper .secondarylink a:visited, a.cancel:link, a.updatelink:link{
	border-radius:3px;
}

/* backup info display */
table.simple td {
	border: 1px solid #eee;
	border-collapse: collapse;
	color: #555;
	font-size: 12px;
	padding: 4px 10px 4px 4px;
}

table.simple {
	width: 100%;
	border: 1px solid #aaa;
}

table.simple td.title {
	width: 125px;
	color: #222;
	font-weight: bold;
}


/* footer */
#footer {
	border-top: 1px solid #e3e3e3;
	text-shadow: 1px 1px 0 rgba(255,255,255,.4);
	margin: calc(var(--gap) * 2) 0 0 0;
	padding: 10px 0;
	font-size: 11px;
	color: #888;
}

#footer p {
	margin: 0 0 8px 0;
}

#footer a:link,
#footer a:visited {
	font-weight: normal;
	color: #888;
}

#footer a:focus, #footer a:hover {
	font-weight: normal;
	color: #333;
}

.editable {
	cursor: pointer;
	transition: background-color .3s ease-in-out;
}

.editable:hover {
	 background: #FCFBB8;
}

.wrapper table td span.ERRmsg {
	color: var(--clr-secondary-lightest);
	font-color: 12px;
}

.wrapper table td span.OKmsg {
	color: #308000;
	font-color: 12px;
}

.wrapper table td span.WARNmsg {
	color: #FFA500;
	font-color: 12px;
}

.wrapper table td span.INFOmsg {
	color: #2B5CB3;
	font-color: 12px;
}

.wrapper table.highlight tr.img-highlight {
	background: #FFFFD1 !important;
}

.wrapper table.highlight tr.img-highlight td a.primarylink {
	font-weight: bold !important;
}

#sidebar .uploadform {
	padding: 5px 0 5px 15px;
}


/* JQuery Uploadify Styles */
.uploadifyQueueItem {
	font-size: 10px;
	padding: 8px 15px;
	width: 190px;
}

.uploadifyError {
	color: var(--clr-secondary-lightest) !important;
}

.uploadifyError .uploadifyProgressBar {
	background-color: var(--clr-secondary-lightest) !important;
}

#sidebar .snav li .cancel {
	float: right;
}

#sidebar .snav li .cancel a:link,
#sidebar .snav li .cancel a:visited,
#sidebar .snav li .cancel a:hover {
	padding: 0 !important;
	margin: 0 4px 0 0 !important;
	width: 11px !important;
	opacity: .8;
	background: transaparent !important;
}

.uploadifyProgress {
	background-color: #FFF;
	margin-top: 5px;
	width: 97%;
}

.uploadifyProgressBar {
	background-color: var(--clr-primary-lightest);
	width: 1px;
	height: 4px;
}

#sidebar .snav li.upload {
	display: block;
	border-radius: 4px;
	margin-left: 13px;
	color: #FFF;
	background: var(--clr-primary-darker);
	font-weight: normal;
}

.uploadifyButton {
	width: 100%;
	border-radius: 4px;
		-webkit-border-radius: 4px;
		-moz-border-radius: 4px;
	display: block;
	font-weight: bold;
	/*color: #AFC5CF;*/
	/*background: #182227;*/
	color: var(--clr-primary-lightest);
	background: var(--clr-primary-darker);
	text-shadow: 1px 1px 0px #0E1316;
	transition: all .3s ease-in-out;
		-webkit-transition: all .3s ease-in-out;
		-moz-transition: all .3s ease-in-out;
		-o-transition: all .3s ease-in-out;
}

.uploadify:hover .uploadifyButton {
	background-color: #0e1316;
	color: #ffffff;
	text-shadow: 1px 1px 0px #000;
}

.uploadifyButtonText{
	padding: 5px 15px 5px 15px;
	display: block;
}

#uploadify object {
	position: absolute;
	left: 0;
	right: 0;
	cursor: pointer;
}

/* image.php */

#img-info {
  width: 50%;
}
#img-code-select, #jcrop_open .instruction {
  color:#666;
  font-size:11px;
}
#img-code-select {
  margin:-10px 0 0 0;
}

/* Image Editor Styles */
textarea.copykit {
	font-family: Consolas, "Andale Mono WT", "Andale Mono", "Lucida Console", "Lucida Sans Typewriter", "DejaVu Sans Mono", "Bitstream Vera Sans Mono", "Liberation Mono", "Nimbus Mono L", Monaco, "Courier New", Courier, monospace;
	font-size: 12px;
	outline: none
	color: #666;
	border: 1px solid #aaa;
	line-height: 17px;
	padding: 4px;
	border-radius: 2px;
	width: 98%;
	height: 70px;
	margin-bottom: 10px;
}

#handw {
	z-index: 1;
	padding: 8px;
	background: #000;
	opacity: .80;
	color: #fff;
	font-size: 11px;
	width: 150px;
	text-align: center;
	margin: -50px 0 0 0;
}

#handw span {
	font-size: 15px;
	font-weight: bold;
}

#jcropform input[type="submit"] {
	margin: 20px 0 0 0;
}

#jcrop_open {
}

a.updatelink:link,
a.updatelink:visited {
	color: var(--clr-secondary-lightest);
	padding: 1px 3px;
}

a.updatelink:hover,
a.updatelink:focus {
	color: #FFF;
	background-color: #2AA206;
	padding: 1px 3px;
}

/* File Browser Styles */
#filebrowser {
	background: #fff;
}

/* plugin styles */
table tr.enabled {
	background: #fff;
}

#maincontent table tr.enabled td span {
	color: #333;
}

table tr.disabled {
	background: #f6f6f6;
}

table tr.disabled td b {
	color: #666;
}

/* Logged out specific styles */
body#install, body#setup, body#index, body#resetpassword {
	background: #f9f9f9;
	padding-top: calc(var(--gap) * 2);
	height: auto;
}

:is(#index, #resetpassword, #install, #setup) #maincontent {
	width: 100%;
}

:is(#index, #resetpassword, #install, #setup) .wrapper {
	width: 480px;
}

:is(#install, #setup, #index, #resetpassword) .main {
	width: 100%;
	text-shadow: 1px 1px 0 #fff;
	background: #fff;
	background: -moz-linear-gradient(top, #f9f9f9 5%, #eeeeee 100%);	/* firefox */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(5%,#f9f9f9), color-stop(100%,#eeeeee));	/* webkit */
}

:is(#setup, #install) .main {
	border-radius: 3px;
	box-shadow: rgba(0,0,0, 0.05) 0px 0px 10px;
	background: #fff;
}
#index p.cta,
#resetpassword p.cta {
	font-size: 11px;
	margin: 0 0 0 0;
	color: #999;
	text-align: center;
}

#index form p,
#resetpassword form p {
	margin-bottom: var(--gap);
}

#index p.cta a,
#resetpassword p.cta a {
	font-weight: normal;
}

#setup #footer,#install #footer {
	border-top: none;
	margin: 0 auto 20px auto;
	text-align: center;
}

.desc {
	font-size: 12px;
	line-height: 17px;
	border-bottom: 1px dotted #ccc;
	padding: 0 0 15px 0;
	margin: 0 0 5px 0;
}

#filter-search {
	margin: 0 0 10px 0;
	display: none;
}

#filter-search input.text {
	width: 250px;
	font-size: 11px;
	padding: 3px;
}

#createfolder {
	font-weight: normal;
	font-size: 11px;
	text-decoration: underline !important;
}

h5 .crumbs, div.h5 .crumbs {
	float: left;
}

#new-folder {
	float: left;
	padding-left: 5px;
}

#new-folder form {
	display: none;
}

#new-folder .cancel {
	font-size: 11px;
	text-shadow: none !important;
}

#new-folder input[type="submit"] {
	font-size: 11px;
	padding: 3px;
}

#new-folder input.text {
	width: 120px;
	font-size: 11px;
	padding: 3px;
}

#edit #dropdown {
	display: inline-block;
	padding: 3px 15px;
	position: relative;
}

#edit #dropdown h6 {
	font-weight: bold;
	font-size: 11px;
	color: #777;
	margin-bottom: 5px;
	font-weight: normal;
	padding-right: 15px;
	display: inline-block;
	background: transparent url('images/utick.png') right center no-repeat;
}

#edit #dropdown:hover h6 {
	visibility: hidden;
}

#edit #dropdown ul {
	display: none;
	text-shadow: 1px 1px 0 #fff;
	list-style: none;
	margin: 0;
	font-size: 11px;
	opacity: 0;
	background: #f6f6f6;
	border: 1px solid #ccc;
	position: absolute;
	top: -5px;
	left: 5px;
	min-width: 100px;
	border-radius: 2px;
	transition: opacity .3s ease-in-out;
	box-shadow: rgba(0,0,0, 0.2) 1px 1px 3px 0;
	overflow: hidden;
}

#edit #dropdown li {
	line-height: 22px;
	border-bottom: #eee 1px solid;
	padding: 0 8px;
}

#edit #dropdown li:last-child {
	border: none;
}

#edit #dropdown li a:link,
#edit #dropdown li a:visited,
#edit #dropdown li a:hover {
	font-weight: normal;
	color: #666;
	text-decoration: none;
	display: block;
}

#edit #dropdown li:hover {
	background: #fff;
}

#edit #dropdown li:hover a {
	color: var(--clr-secondary-lightest);
}

#edit #dropdown li.alertme:hover {
	background: var(--clr-secondary-lightest);
}

#edit #dropdown li.alertme:hover a {
	color: #FFF;
	text-shadow: none;
}

#edit #dropdown:hover ul {
	display: block;
	opacity: 1;
}

.wrapper #maincontent ul#menu-order {
	list-style: none;
	margin: 0 0 25px 0;
}

#menu-order li {
	text-shadow: 1px 1px 0px rgba(255,255,255,.3);
	cursor: move;
	display: flex;
	margin: 2px 0;
	border: 1px solid #eee;
	background: #fbfbfb;
	padding: 5px 10px;
	box-sizing: content-box;
}

#menu-order li:hover {
	border: 1px solid #ccc;
	background: #f6f6f6;
}

#menu-order li .order {
	font-weight: 700;
}

#menu-order li .menu {
	margin-left: 2ch;
	margin-right: 2ch;
}

#menu-order li .title {
	color: #666;
	font-style: italic;
	margin-left: auto;
}

#menu-order li.placeholder-menu {
	background: #eee;
	border: 1px solid #eee;
}

#gsdebug{
	border: 1px solid var(--clr-secondary-lightest);
	padding: 5px;
	background: white;
	height: 200px;
	overflow: scroll;
	margin-bottom: 20px;
}

.ajaxwait {
	background-image: url('images/ajax.gif');
	background-position: center;
	background-repeat: no-repeat;
}

.ajaxwait_dark {
	background-image: url('images/ajax_dark.gif');
}

.ajaxwait_tint_dark {
	background-color: #F6F6F6;
}

a.disabled:link,
a.disabled:visited {
	pointer-events: none;
	cursor: default;
	text-decoration: none !important;
	color: #CCC !important;
}

/* override handle uicolor #FFFFFF, prevents invisible seperators */
.cke_toolbar_separator {
	box-shadow: 1px 0 1px rgba(0, 0, 0, 0.14) !important;
}

#theme-selector, #theme-files-selector {
	display: flex;
	flex-direction: row;
	flex-wrap: nowrap;
	justify-content: space-between;
	align-items: center;
	gap: var(--gap);
	margin-bottom: 20px;
}

#theme-folder, #theme-select {
	flex: 1;
}

#theme-files {
	width: 380px;
}

#suggested-url {
	color: var(--clr-secondary-lightest);
	margin-top: -15px;
}
