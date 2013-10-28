{**
 *  \details &copy; 2011  Open Ximdex Evolution SL [http://www.ximdex.org]
 *
 *  Ximdex a Semantic Content Management System (CMS)
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published
 *  by the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  See the Affero GNU General Public License for more details.
 *  You should have received a copy of the Affero GNU General Public License
 *  version 3 along with Ximdex (see LICENSE file).
 *
 *  If not, visit http://gnu.org/licenses/agpl-3.0.html.
 *
 *  @author Ximdex DevTeam <dev@ximdex.com>
 *  @version $Revision$
 *}
<form method="post" id="mu_action" action="{$action_url}" enctype="multipart/form-data">
<div class="action_header">
   	<h2 class="action">{t}Create project{/t}</h2>
   	<fieldset class="buttons-form">
		<!--{button type="reset" label='Reset' class='btn' }-->
		{button label="Create project" class='validate btn main_action' }
	</fieldset>
</div>
<div class="action_content">
	<div class="theme-selection" style="width:100%">
	<label for="name" class="">{t}Name{/t}</label>

		<input type="text" name="name" id="name" value="{$name}" class='cajaxg validable not_empty long'/>
		<a href="#" class="advanced-btn">Advanced settings</a>
		<div class="advanced-settings">{t}Publication channels{/t}
			{foreach from=$channels key=index item=channelData }
		        <span>
		          	<input type="checkbox" class="validable canales check_group__canales"
								name="channels_listed[{$channelData.id}]" id="p_{$channelData.id}" />
			         <label for="p_{$channelData.id}" class="inline nofloat">{$channelData.name}</label> 	
		        </span>
			        
			        
			        <p class="message_warning">{t}There are no channels created in the system{/t}</p>
			        {/foreach}</div>


			<label for="theme">{t}Theme{/t}</label>
			<ul class="themes">
				{foreach from=$themes key=index item=theme}
				<li class="theme">
					<div class="img_container">
						<img src="modules/XSparrow/themes/{$theme.name}/{$theme.name}.png" alt="{$theme.title}">
					<div class="actions"><a href="" class="icon select" data-theme="{$theme.name}">Select</a><a data-theme="{$theme.name}" href="" class="icon custom">Custom</a></div>
					</div>
					<p class="title">{$theme.title}</p>
					<p class="type">{$theme.description}</p>
				</li>
				{/foreach}
			</ul>

		</div>	

			<div class="customize-template-form">
			<div class="custom_options">

					<div class="site_header">
						<h3 class="opened icon">{t}Header{/t}</h3>
						<dl>
							<dt>Layout</dt>
							<dd><select id="select1" name="layout" class="ximdexInput small horizontal icon no-collapsable">
									<option value="1col" selected="selected"></option>
									<option value="2col"></option>
									<option value="3col" ></option>
								</select></dd>
							<dt>Background</dt>
							<dd>
								<input type="color" name='background-color' data-tag="header" data-attribute="background-color" id="secundary_color" value="#006b6c" class='input_colorpicker button bg-color'/>
								<span class="img-uploader icon button"><input type="file" data-tag="header" data-attribute="background-image" /></span>
								
							</dd>
							<span class="background-selectors hidden">
								<select id="select1" name="bg-position" class="ximdexInput small vertical collapsable icon bg-position button" data-tag="header" data-attribute="background-position">
									<option value="lt" selected="selected"></option>
									<option value="rt"></option>
									<option value="lb" ></option>
									<option value="rb" ></option>
									<option value="cc" ></option>
								</select>
								<select id="select1" name="bg-repeat" class="ximdexInput small vertical collapsable icon bg-repeat button" data-tag="header" data-attribute="background-repeat">
									<option value="repeat-x" selected="selected"></option>
									<option value="repeat-y"></option>
									<option value="no-repeat" ></option>
									<option value="repeat" ></option>
								</select>
								<button type="" class="remove-image" data-tag="header">Remove</button>
							</span>
							<dt>Text</dt>
							<dd>
								<input type="color" name='font-color' id="secundary_color" value="#006b6c" class='input_colorpicker button font-color icon' data-tag="header" data-attribute="font-color"/>
								<select id="select1" name="text-align" data-tag="header" data-attribute="align" class="text-align ximdexInput small vertical collapsable icon button">
									<option value="left" selected="selected"></option>
									<option value="center"></option>
									<option value="right" ></option>
									<option value="justify" ></option>
								</select>
								<span class="font-size icon button">
									<div class="range-bg"><input data-tag="header-title" data-attribute="font-size" type="range" name="rango" id="rango" min="80" max="120" step="1"/></div>
								</span>
								<select id="fonts" class="font-selector icon button" data-tag="header" data-attribute="font-family">
									<option value="Chelsea Market">Chelsea Market</option>
									<option value="Droid Serif" selected="selected">Droid Serif</option>
									<option value="Ruluko">Ruluko</option>
									<option value="Ruda">Ruda</option>
									<option value="Magra">Magra</option>
									<option value="Esteban">Esteban</option>
									<option value="Lora">Lora</option>
									<option value="Jura">Jura</option>
									<option value="Ubuntu">Ubuntu</option>
								</select>

							</dd>
						</dl>

					</div>
<div class="site_body">
						<h3 class="closed icon">{t}Body{/t}</h3>
						<dl>
							<dt>Layout</dt>
							<dd><select id="select1" name="layout" class="ximdexInput small horizontal icon no-collapsable">
									<option value="1col" selected="selected"></option>
									<option value="2col"></option>
									<option value="3col" ></option>
								</select></dd>
							<dt>Background</dt>
							<dd>
								<input type="color" name='secundary_color' id="secundary_color" value="#006b6c" class='input_colorpicker button bg-color' data-tag="body" data-attribute="background-color"/>
								<span class="img-uploader icon button"><input type="file" data-tag="body" data-attribute="background-image" /></span>															</dd>
							<span class="background-selectors hidden">
								<select id="select1" name="bg-position" class="ximdexInput small vertical collapsable icon bg-position button" data-tag="body" data-attribute="background-position">
									<option value="lt" selected="selected"></option>
									<option value="rt"></option>
									<option value="lb" ></option>
									<option value="rb" ></option>
									<option value="cc" ></option>
								</select>
								<select id="select1" data-tag="body" data-attribute="background-repeat" name="bg-repeat" class="ximdexInput small vertical collapsable icon bg-repeat button">
									<option value="repeat-x" selected="selected"></option>
									<option value="repeat-y"></option>
									<option value="no-repeat" ></option>
									<option value="repeat" ></option>
								</select>
								<button type="" class="remove-image" data-tag="body">Remove</button>
							</span>
							<dt>Text</dt>
							<dd>
								<input type="color" name='secundary_color' data-tag="body" data-attribute="font-color" value="#006b6c" class='input_colorpicker button font-color icon'/>
								<select id="select1" name="text-align" data-tag="content" data-attribute="align" class="text-align ximdexInput small vertical collapsable icon button">
									<option value="left" selected="selected"></option>
									<option value="center"></option>
									<option value="right" ></option>
									<option value="justify" ></option>
								</select>
								<span class="font-size icon button">
									<div class="range-bg"><input data-tag="body" data-attribute="font-size" type="range" name="rango" id="rango" min="80" max="120" step="1"/></div>
								</span>
								<select id="fonts" class="font-selector icon button" data-tag="body" data-attribute="font-family">
									<option value="Chelsea Market">Chelsea Market</option>
									<option value="Droid Serif" selected="selected">Droid Serif</option>
									<option value="Ruluko">Ruluko</option>
									<option value="Ruda">Ruda</option>
									<option value="Magra">Magra</option>
									<option value="Esteban">Esteban</option>
									<option value="Lora">Lora</option>
									<option value="Jura">Jura</option>
									<option value="Ubuntu">Ubuntu</option>
								</select>

							</dd>
						</dl>

					</div>
					<div class="site_footer">
						<h3 class="closed icon">{t}Footer{/t}</h3>
						<dl>
							<dt>Layout</dt>
							<dd><select id="select1" name="layout" class="ximdexInput small horizontal icon no-collapsable">
									<option value="1col" selected="selected"></option>
									<option value="2col"></option>
									<option value="3col" ></option>
								</select></dd>
							<dt>Background</dt>
							<dd>
								<input type="color" data-tag="footer" data-attribute="background-color"  value="#006b6c" class='input_colorpicker button bg-color'/>
								<span class="img-uploader icon button"><input type="file" data-tag="footer" data-attribute="background-image" /></span>
								<select id="select1" name="bg-position" class="ximdexInput small vertical collapsable icon bg-position button">
									<option value="lt" selected="selected"></option>
									<option value="rt"></option>
									<option value="lb" ></option>
									<option value="rb" ></option>
									<option value="cc" ></option>
								</select>
								<select id="select1" name="bg-repeat" class="ximdexInput small vertical collapsable icon bg-repeat button">
									<option value="repeat-x" selected="selected"></option>
									<option value="repeat-y"></option>
									<option value="no-repeat" ></option>
									<option value="repeat" ></option>
								</select>
							</dd>
							<dt>Text</dt>
							<dd>
								<input type="color" name='secundary_color' id="secundary_color" value="#006b6c" class='input_colorpicker button font-color icon' data-tag="footer" data-attribute="font-color"/>
								<select id="select1" name="text-align" class="text-align ximdexInput small vertical collapsable icon button">
									<option value="left" selected="selected"></option>
									<option value="center"></option>
									<option value="right" ></option>
									<option value="justify" ></option>
								</select>
								<span class="font-size icon button">
									<div class="range-bg"><input type="range" name="rango" id="rango" min="0" max="100" step="1"/></div>
								</span>
								<select id="fonts" class="font-selector icon button">
									<option value="Chelsea Market">Chelsea Market</option>
									<option value="Droid Serif" selected="selected">Droid Serif</option>
									<option value="Ruluko">Ruluko</option>
									<option value="Ruda">Ruda</option>
									<option value="Magra">Magra</option>
									<option value="Esteban">Esteban</option>
									<option value="Lora">Lora</option>
									<option value="Jura">Jura</option>
									<option value="Ubuntu">Ubuntu</option>
								</select>
							</dd>
						</dl>
					</div>


	
			</div>

			<div class="templatePreview" style="position:absolute; left:28%; right:0; top:0; bottom:0">
				<iframe id="xsparrow-preview" class="xsparrow-preview-frame" style="width:100%; position: relative; height:100%; float:right" frameborder="0" src="{$_URL_ROOT}/xmd/loadaction.php?action=createproject&mod=XSparrow&method=loadPreview" scrolling="auto">

				</iframe>
			</div>
			<input type="hidden" name="theme" value="" class="theme"/>
			<input type="hidden" name="xml" value="" class="xmlContent"/>
			
</div>
</form>
