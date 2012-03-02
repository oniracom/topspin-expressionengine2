<div>
<h1>Documentation</h1>

<h3>Method:</h3>
<p>{exp:topspin:offers}</p>

<h4>Notes:</h4>
<p>There are 2 types of offers in topspin: buy buttons and media widgets.  Wrap your template code in {if buy_button}{/if} or {if widget}{/if} to make separate formatting for each type.
</p>
<h4>Conditionals:</h4>
<ul>
<li>{if buy_button} {/if}</li>
<li>{if widgets} {/if}</li>
<li>All variables within the module can be used as conditionals</li>
</ul>
<h4>Variables:</h4>
<strong>Buy Buttons:</strong>
<ul>
<li>{item_price}</li>
<li>{item_name}</li>
<li>{item_image}</li>
<li>{item_description}</li>
<li>{item_embed}</li>
<li>Standard EE variables: {switch}, {count}, and {total_results}</li>
</ul>
<strong>Widgets:</strong>
<ul><li>{item_embed}</li></ul>
<h4>Parameters:</h4>
<ul>
<li>tag="my tag name"
<li>limit="10"</li>
</ul>
<strong>Example:</strong>
<p>
{exp:topspin:offers tag="my great tag" limit="10"}
<br>
{if buy_button}
<br>
<code>	
		&lt;div class="float-left productBlock {switch="|||last"}"&gt;
		<br>
			&lt;div class="topspin-grid-item-image">{if item_image}&lt;img src="{item_image}" width="150" height="150"/&gt;{/if}&lt;/div&gt;
			<br>
			&lt;h3 class="text-center caps"&gt;{item_name}&lt;/h3&gt;
			<br>
			&lt;div class="topspin-grid-item-price">{item_price}&lt;/div&gt;
			<br>
			&lt;div class="topspin-grid-item-embed">{item_embed}&lt;/div&gt;
			<br>
		&lt;/div>
		<br>
{/if}
<br>
{if widget}
<br>
{embed_code}
<br>
{/if}
</code>

<br>
{/exp:topspin:offers}
</p>


</div>