Feature: Attachments parser
    As a software developer
    In order to find duplicate attachments content
    I should see ids of duplicated attachments
        separated by "space" character



    Scenario: Displaying duplicates from file when duplicates are present
        Given There is file with lines:
         | line                |
         | Attachment id: 1    |
         | fsjdhf483493h934hfs |
         | sfwqus483493u934usf |
         | Attachment id: 2    |
         | fsjdhf483493h934hfs |
         | sfwqus483493u934usf |
         | Attachment id: 3    |
         | aasksdshfksjdhfkhds |
         | Attachment id: 4    |
         | sfwqus483493u934usf |
        When I run console sctipt
        Then I should see following output
         | output |
         | 1 2    |
         | 3      |
         | 4      |
