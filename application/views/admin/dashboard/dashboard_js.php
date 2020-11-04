
<script>
    var weekly_payments_statistics;
    var user_dashboard_visibility = <?php echo $user_dashboard_visibility; ?>;
    $(function() {
        $( "[data-container]" ).sortable({
            connectWith: "[data-container]",
            helper:'clone',
            handle:'.widget-dragger',
            tolerance:'pointer',
            forcePlaceholderSize: true,
            placeholder: 'placeholder-dashboard-widgets',
            start:function(event,ui) {
                $("body,#wrapper").addClass('noscroll');
                $('body').find('[data-container]').css('min-height','20px');
            },
            stop:function(event,ui) {
                $("body,#wrapper").removeClass('noscroll');
                $('body').find('[data-container]').removeAttr('style');
            },
            update: function(event, ui) {
                if (this === ui.item.parent()[0]) {
                    var data = {};
                    $.each($("[data-container]"),function(){
                        var cId = $(this).attr('data-container');
                        data[cId] = $(this).sortable('toArray');
                        if(data[cId].length == 0) {
                            data[cId] = 'empty';
                        }
                    });
                    $.post(admin_url+'staff/save_dashboard_widgets_order', data, "json");
                }
            }
        });

        $('body').on('click','#viewWidgetableArea',function(e){
            e.preventDefault();

            if(!$(this).hasClass('preview')) {
                $(this).html("<?php echo _l('hide_widgetable_area'); ?>");
                $('[data-container]').append('<div class="placeholder-dashboard-widgets pl-preview"></div>');
            } else {
                $(this).html("<?php echo _l('view_widgetable_area'); ?>");
                $('[data-container]').find('.pl-preview').remove();
            }

            $('[data-container]').toggleClass('preview-widgets');
            $(this).toggleClass('preview');
        });

        var $widgets = $('.widget');
        var widgetsOptionsHTML = '';
        widgetsOptionsHTML += '<div id="dashboard-options">';
        widgetsOptionsHTML += "<h4><i class='fa fa-question-circle' data-toggle='tooltip' data-placement=\"bottom\" data-title=\"<?php echo _l('widgets_visibility_help_text'); ?>\"></i> <?php echo _l('widgets'); ?></h4><a href=\"<?php echo admin_url('staff/reset_dashboard'); ?>\"><?php echo _l('reset_dashboard'); ?></a>";

        widgetsOptionsHTML += ' | <a href=\"#\" id="viewWidgetableArea"><?php echo _l('view_widgetable_area'); ?></a>';
        widgetsOptionsHTML += '<hr class=\"hr-10\">';

        $.each($widgets,function(){
            var widget = $(this);
            var widgetOptionsHTML = '';
            if(widget.data('name') && widget.html().trim().length > 0) {
                widgetOptionsHTML += '<div class="checkbox checkbox-inline">';
                var wID = widget.attr('id');
                wID = wID.split('widget-');
                wID = wID[wID.length-1];
                var checked= ' ';
                var db_result = $.grep(user_dashboard_visibility, function(e){ return e.id == wID; });
                if(db_result.length >= 0) {
                    // no options saved or really visible
                    if(typeof(db_result[0]) == 'undefined' || db_result[0]['visible'] == 1) {
                        checked = ' checked ';
                    }
                }
                widgetOptionsHTML += '<input type="checkbox" class="widget-visibility" value="'+wID+'"'+checked+'id="widget_option_'+wID+'" name="dashboard_widgets['+wID+']">';
                widgetOptionsHTML += '<label for="widget_option_'+wID+'">'+widget.data('name')+'</label>';
                widgetOptionsHTML += '</div>';
            }
            widgetsOptionsHTML += widgetOptionsHTML;
        });

        $('.screen-options-area').append(widgetsOptionsHTML);
        $('body').find('#dashboard-options input.widget-visibility').on('change',function(){
          if($(this).prop('checked') == false) {
            $('#widget-'+$(this).val()).addClass('hide');
        } else {
            $('#widget-'+$(this).val()).removeClass('hide');
        }

        var data = {};
        var options = $('#dashboard-options input[type="checkbox"]').map(function() {
            return { id: this.value, visible: this.checked ? 1 : 0 };
        }).get();

        data.widgets = options;
/*
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
*/
        $.post(admin_url+'staff/save_dashboard_widgets_visibility',data).fail(function(data) {
            // Demo usage, prevent multiple alerts
            if($('body').find('.float-alert').length == 0) {
                alert_float('danger', data.responseText);
            }
        });
    });

        var tickets_chart_departments = $('#tickets-awaiting-reply-by-department');
        var tickets_chart_status = $('#tickets-awaiting-reply-by-status');
        var leads_chart = $('#leads_status_stats');
		var leads_customer = $('#leads_customer');
        var projects_chart = $('#projects_status_stats');

        if (tickets_chart_departments.length > 0) {
            // Tickets awaiting reply by department chart
            var tickets_dep_chart = new Chart(tickets_chart_departments, {
                type: 'doughnut',
                data: <?php echo $tickets_awaiting_reply_by_department; ?>,
            });
        }
        if (tickets_chart_status.length > 0) {
            // Tickets awaiting reply by department chart
            new Chart(tickets_chart_status, {
                type: 'doughnut',
                data: <?php echo $tickets_reply_by_status; ?>,
                options: {
                   onClick:function(evt){
                    onChartClickRedirect(evt,this);
                }
            },
        });
        }
        if (leads_chart.length > 0) {
            // Leads overview status
            new Chart(leads_chart, {
                type: 'pie',
                data: <?php echo $leads_status_stats; ?>,
                options:{
                    maintainAspectRatio:false,
                    onClick:function(evt){
                        onChartClickRedirect(evt,this);
                    }
                }
            });
			
			
        }
		if (leads_customer.length > 0) {
            // Leads overview status
            new Chart(leads_customer, {
                type: 'pie',
                data: <?php echo $customer_status_stats; ?>,
				
                options:{
                    maintainAspectRatio:false,
                    onClick:function(evt){
                        onChartClickRedirect(evt,this);
                    }
                }
            });
        }
        if(projects_chart.length > 0){
            // Projects statuses
            new Chart(projects_chart, {
                type: 'doughnut',
                data: <?php echo $projects_status_stats; ?>,
                options: {
                    maintainAspectRatio:false,
                    onClick:function(evt){
                       onChartClickRedirect(evt,this);
                   }
               }
           });
        }
        // Payments statistics
        init_weekly_payment_statistics( <?php echo $weekly_payment_stats; ?> );
        $('select[name="currency"]').on('change', function() {
            init_weekly_payment_statistics();
        });
    });
	
	
	google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Customer Type'],
		  <?php 
			$customer = $this->leads_model->get_customer_type();
			foreach($customer as $_items){
			$total = $this->dashboard_model->get_lead_customer_type($_items['code']);
			if($total==null)
			{ 
				$totalamt = 0; 
			}
			else{
				$totalamt = $total;
			}
		  ?>
          ['<?php echo $_items['name']; ?>', <?php echo $totalamt; ?>],
          
			<?php 
			$totalamt = 0;
			} ?>
        ]);

        var options = {
          title: '',
		  sliceVisibilityThreshold:0
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }
	
	google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart1);

      function drawChart1() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
		  <?php 
			$lead_status = $this->leads_model->get_status();
			foreach($lead_status as $_items){
			$total = $this->dashboard_model->get_lead_status($_items['id']);
			if($total==null)
			{ 
				$totalamt = 0; 
			}
			else{
				$totalamt = $total;
			}
		  ?>
          ['<?php echo $_items['name']; ?>', <?php echo $totalamt; ?>],
          
			<?php 
			$totalamt = 0;
			} ?>
        ]);

        var options = {
          title: '',
		  sliceVisibilityThreshold:0
        };

        var chart1 = new google.visualization.PieChart(document.getElementById('pie_lead_status'));

        chart1.draw(data, options);
    }
	
	  google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart4);
    function drawChart4() {
      var data = google.visualization.arrayToDataTable([
		 ["Element", "Total",{ role: "style" }],
		<?php 
			$customer = $this->leads_model->get_customer_type();
			$i=0;
			foreach($customer as $_items){
			$total = $this->dashboard_model->get_lead_customer_type($_items['code']);
			if($total==null)
			{ 
				$totalamt = 0; 
			}
			else{
				$totalamt = $total;
			}
		  ?>
       
        ["<?php echo $_items['name']; ?>", <?php echo $totalamt; ?>, <?php if($i==0){ ?> "#3366cc" <?php }elseif($i==1){ ?> '#dc3912' <?php }elseif($i==2){ ?> '#ff9900' <?php }elseif($i==3){ ?> '#109618' <?php }elseif($i==4){ ?> '#990099' <?php }elseif($i==5){ ?> '#0099c6' <?php } ?>],
		<?php 
		$i++;
		} ?>
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "",
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
      chart.draw(view, options);
  }

  google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart3);
    function drawChart3() {
      var data = google.visualization.arrayToDataTable([
		 ["Element", "Total",{ role: "style" }],
		<?php 
			$lead_status = $this->leads_model->get_status();
			$i = 0;
			foreach($lead_status as $_items){
			$total = $this->dashboard_model->get_lead_status($_items['id']);
			if($total==null)
			{ 
				$totalamt = 0; 
			}
			else{
				$totalamt = $total;
			}
		  ?>
       
        ["<?php echo $_items['name']; ?>", <?php echo $totalamt; ?>, <?php if($i==0){ ?> "#3366cc" <?php }elseif($i==1){ ?> '#dc3912' <?php }elseif($i==2){ ?> '#ff9900' <?php }elseif($i==3){ ?> '#109618' <?php }elseif($i==4){ ?> '#990099' <?php }elseif($i==5){ ?> '#0099c6' <?php } ?>],
		<?php 
		$i++;
		} ?>
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "",
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values1"));
      chart.draw(view, options);
  }


    function init_weekly_payment_statistics(data) {
        if ($('#weekly-payment-statistics').length > 0) {

            if (typeof(weekly_payments_statistics) !== 'undefined') {
                weekly_payments_statistics.destroy();
            }
            if (typeof(data) == 'undefined') {
                var currency = $('select[name="currency"]').val();
                $.get(admin_url + 'home/weekly_payments_statistics/' + currency, function(response) {
                    weekly_payments_statistics = new Chart($('#weekly-payment-statistics'), {
                        type: 'bar',
                        data: response,
                        options: {
                            responsive:true,
                            scales: {
                                yAxes: [{
                                  ticks: {
                                    beginAtZero: true,
                                }
                            }]
                        },
                    },
                });
                }, 'json');
            } else {
                weekly_payments_statistics = new Chart($('#weekly-payment-statistics'), {
                    type: 'bar',
                    data: data,
                    options: {
                        responsive: true,
                        scales: {
                            yAxes: [{
                              ticks: {
                                beginAtZero: true,
                            }
                        }]
                    },
                },
            });
            }

        }
    }
</script>
