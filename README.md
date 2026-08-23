# Curriculum

Just my personal curriculum vitae as an over-engineered interactive website, built with Laravel.

## Models

### People (`Person`)

It simply represents the owner of a CV. It is used to store all the data related to a person. Typically you will only have one record.

Deleting a Person also deletes all of their CVs.

### Curricula Vitae (`CurriculumVitae`)

The central model, aggregating all information in a personal curriculum vitae, as its name suggests. It is bound to one person. It also holds settings about which piece of information to display.

One CV can be set as the default, so that it appears directly on the homepage.
