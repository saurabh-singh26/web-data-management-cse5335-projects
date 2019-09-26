<all_results>
	<result_1>
		{ 
			for $x in doc("reed.xml")//course
			where $x/subj="MATH" and $x/place/building="LIB" and $x/place/room="204"
			return <course> 
						{ $x/title }
						{ $x/instructor }
						{ $x/time/start_time }
						{ $x/time/end_time }
					</course>
		}
	</result_1>


	<result_2>
		{
			for $x in distinct-values(doc("reed.xml")//course/title)
			return <course>
						<title> { $x } </title>
						<instructors>
							{ for $y in distinct-values(doc("reed.xml")//course[title = $x]/instructor)
							return <instructor>
										{ $y }
									</instructor>
							}
						</instructors>
					</course>
		}
	</result_2>

	<result_3>
		{
			for $x in distinct-values(doc("reed.xml")//course/subj)
			return <dept>{$x, count(distinct-values(doc("reed.xml")//course[subj = $x]//title))}</dept>
		}
	</result_3>

	<result_4>
		{
			for $x in distinct-values(doc("reed.xml")//course/instructor)
			return <instructor>
						<name> { $x } </name>
						<count> { count(doc("reed.xml")//course[instructor = $x]) } </count>
					</instructor>
		}
	</result_4>

	<result_5>
		{
			for $x in distinct-values(doc("reed.xml")//course/instructor)
			return <instructor>
						<name> { $x } </name>
						<titles> 
							{ for $y in distinct-values(doc("reed.xml")//course[instructor = $x]/title)
							return <title>
										{ $y }
									</title>}
						</titles>
					</instructor>
		}
	</result_5>
</all_results>

