Real-Time Social Media Sentiment Analysis Dashboard

NLP · BERT · Python · Laravel · SQL · AWS

📌 Project Overview

This project presents a real-time sentiment analytics pipeline and interactive dashboard developed as part of an MSc dissertation at Sheffield Hallam University.

The system captures social media comments, processes them through a fine-tuned BERT sentiment model, and visualises sentiment trends in near real time to support data-driven business decision-making in the retail sector.

Case study: Customer sentiment analysis based on Facebook page comments (retail context inspired by Tesco).

🎯 Problem Statement

Retail organisations receive large volumes of unstructured customer feedback on social media platforms.
Manually analysing this data is time-consuming, error-prone, and not scalable, which limits the ability of decision-makers to:

Detect emerging customer issues early

Measure campaign or service impact

Respond proactively to sentiment changes

This project addresses the gap by building an automated, real-time sentiment analytics system that transforms social media comments into actionable insights.

✅ Objectives

Design and implement a real-time data pipeline for collecting social media comments

Fine-tune a BERT-based sentiment classification model for retail-related text

Store and manage sentiment outputs in a relational database

Develop an interactive web dashboard to visualise sentiment trends

Deploy the end-to-end solution on cloud infrastructure

🏗️ System Architecture

High-level workflow:

Data Collection:

Python scripts fetch Facebook comments via API

Comments stored in SQL database with timestamps

Sentiment Analysis Pipeline:

Hourly batch of comments passed to a fine-tuned BERT model

Sentiment classified into: Negative, Neutral, Positive, Very Positive

Backend Orchestration:

Laravel controllers execute Python scripts

Scheduled jobs automate scraping and inference

Data Storage:

SQL database stores: Raw comments, Hourly sentiment aggregates, Daily and monthly summaries

Frontend Dashboard:

AJAX-powered Laravel views

Interactive charts update without page reloads

🧠 Machine Learning & NLP

Model: nlptown/bert-base-multilingual-uncased-sentiment

Frameworks: TensorFlow, Hugging Face Transformers

Training Data:

250k+ labelled customer reviews (Kaggle – TeePublic dataset)

Techniques Used:

Text preprocessing & tokenisation

Class balancing (resampling)

Fine-tuning with train/validation split

Model evaluation and persistence

📊 Dashboard Features

Live Sentiment (Last Hour)

Donut chart showing real-time sentiment distribution

Hourly Sentiment Analysis

Line charts with day-level filtering

Daily & Monthly Trends

Aggregated sentiment evolution over time

Interactive Visuals

Hover tooltips

Date and month navigation

AJAX-based real-time updates

Responsive Design

Works on desktop and mobile devices

🛠️ Technologies Used

Data & ML:Python, TensorFlow, Hugging Face Transformers, Pandas, NumPy

Backend: Laravel (PHP), REST APIs, Cron jobs / scheduled tasks

Frontend: Laravel Blade, jQuery & AJAX, Chart.js

Database: SQL (relational schema for comments & sentiment metrics)

Cloud & Deployment: AWS EC2 (Windows Server 2022)

📈 Key Outcomes

Successfully automated real-time sentiment monitoring

Demonstrated practical application of BERT in business analytics

Delivered a decision-support dashboard suitable for retail stakeholders

Deployed a production-style ML pipeline integrating Python and Laravel


🔗 Links

🌐 Portfolio Website: https://www.manujasprojects.co.uk

📬 Contact

Manuja Palamakumbura
Data Analyst | BI Analyst | ML & Analytics Projects
🔗 LinkedIn: https://www.linkedin.com/in/manuja-palamakumbura/
