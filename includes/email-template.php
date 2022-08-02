<?php
    // Sending mail function
    function srpwp_mail_function ( $customer_email, $customer_name, $srpwp_payment_type, $srpwp_amount_val ) {
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: The13thMan <'.$customer_email.'>' . "\r\n";
        $subject 	= "We just received your donation";
        // message
        $message = '
        <html>
            <head>
                <title>We just received your donation</title>
                <style>
                    table {
                        border-collapse: collapse;
                        width: 100%;
                    }
                    th, td {
                        text-align: left;
                        padding: 8px;
                    }
                    tr:nth-child(even){background-color: #f2f2f2}
                    th {
                        background-color: #04AA6D;
                        color: white;
                    }
                </style>
            </head>
            <body>
                <p>Hello '.$customer_name.', <br />We just received your donation, details are given below:</p>
                <table>
                    <tr>
                        <th>Fields</th>
                        <th>Values</th>
                    </tr>
                    <tr>
                        <td>Payment Type:</td>
                        <td>'.$srpwp_payment_type.'</td>
                    </tr>
                    <tr>
                        <td>Amount:</td>
                        <td>'.$srpwp_amount_val.'</td>
                    </tr>
                </table>

                <p>If you have any query then do not hesitate to contact us. <br /><br /><br />Thanks & Regards</p>
            </body>
        </html>
        ';

        // Send an email to the user
        wp_mail( $customer_email, $subject, $message, $headers );
    }
    ?>