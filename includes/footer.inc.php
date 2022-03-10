				<div class="clearfix">
					&nbsp;
				</div>
			</div>
			
			<div id="footer">
				<div id="uoalogo">
					&nbsp;
				</div>
				<div id="copyright">
					<p>This site is maintained and operated by the Medi-CAL Unit.</p>
					<p>For more information please contact us (medi-cal@abdn.ac.uk).</p>
					<p>The University of Aberdeen is not responsible for the content of external internet sites.</p>
				</div>
				<div id="calunitlogo">
					&nbsp;
				</div>
			</div>
		</div>

		<script type="text/javascript">
			/* <![CDATA[ */
				//var searchtext = 'Search by Username';
				
				/**
				 * Sets the Search box to display Search if
				 * it contains no value
				 **/
				function setSearchText(element, searchtext){
					if($(element).val() == searchtext){
						$(element).val('');
					} else if($(element).val() == ''){
						$(element).val(searchtext);
					}
				}
				
				//bind an onclick & onblur handlers to the search box
				$("#un").bind('click', function(e){
										  setSearchText("#un",'Search by Username');
									  });
				$("#un").bind('blur', function(e){
										  setSearchText("#un",'Search by Username');
									  });

				//bind an onclick & onblur handlers to the search box
				$("#n").bind('click', function(e){
										  setSearchText("#n",'Search by Name');
									  });
				$("#n").bind('blur', function(e){
										  setSearchText("#n",'Search by Name');
									  });

				
				//forcibly set the search text on page load
				setSearchText("#un",'Search by Username');
				setSearchText("#n",'Search by Name');
				
			/* ]]> */
		</script>
	</body>
</html>