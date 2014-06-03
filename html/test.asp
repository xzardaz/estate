<%@ Page Language="C#"%>
<html>
<head>
<title>Hello World!</title>
</head>
<body>
   <% for (int i=0; i <6; i++) { %>
      <font size="<%=i%>"> I don't want the world, I just want your half </font> <br>
   <% }
   Response.Write("<p><cite>They Might Be Giants - Ana Ng</cite>");
%>
</body>
</html>